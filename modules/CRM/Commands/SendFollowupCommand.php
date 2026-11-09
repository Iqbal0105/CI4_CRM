<?php

declare(strict_types=1);

namespace Modules\CRM\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\CRM\Services\WhatsAppGatewayService;

class SendFollowupCommand extends BaseCommand
{
    protected $group       = 'CRM';
    protected $name        = 'crm:send-followup';
    protected $description = 'Proses antrean follow-up WhatsApp purnajual H+0, H+3, H+7 secara otomatis.';

    public function run(array $params)
    {
        CLI::write('Memulai pengiriman follow-up WhatsApp purnajual...', 'yellow');

        $db = \Config\Database::connect();
        $waService = new WhatsAppGatewayService();
        $today = date('Y-m-d');

        // Fetch pending schedules due today or earlier (limit 20 per run for rate-limit protection)
        $schedules = $db->table('crm_transaction_followup_labels')
            ->where('status', 'pending')
            ->where('target_date <=', $today)
            ->where('retry_count <', 3)
            ->limit(20)
            ->get()
            ->getResultArray();

        if (empty($schedules)) {
            CLI::write('Tidak ada jadwal follow-up yang perlu dikirim saat ini.', 'green');
            return;
        }

        $templates = [
            'H+0' => "Halo kak {nama}, terima kasih telah berbelanja di Metafora! Pesanan Anda #{nomor_resi} ({produk}) sedang disiapkan/dikirim. Pantau perjalanan paket Anda ya!",
            'H+3' => "Halo kak {nama}, bagaimana kabar paket produk {produk}? Semoga sudah sampai dengan selamat dan sesuai harapan ya. Jika butuh panduan, silakan balas chat ini ya kak!",
            'H+7' => "Halo kak {nama}, terima kasih telah mempercayai Metafora untuk {produk}! Jangan lupa berikan ulasan terbaik Anda. Gunakan kode promo REPEAT10 untuk diskon belanja berikutnya ya!",
        ];

        $sentCount = 0;
        $failedCount = 0;

        foreach ($schedules as $sch) {
            // Fetch order items & resi
            $order = $db->table('sales_order so')
                ->select('so.nomor_so, pm.nomor_resi')
                ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left')
                ->where('so.id', $sch['order_id'])
                ->get()
                ->getRowArray();

            $itemRow = $db->table('sales_order_item soi')
                ->select('p.nama_produk')
                ->join('produk p', 'p.id = soi.produk_id', 'left')
                ->where('soi.so_id', $sch['order_id'])
                ->get()
                ->getRowArray();

            $vars = [
                'nama'       => $sch['customer_nama'],
                'nomor_resi' => $order['nomor_resi'] ?? $order['nomor_so'] ?? '-',
                'produk'     => $itemRow['nama_produk'] ?? 'pesanan Anda',
            ];

            $tmpl = $templates[$sch['label_code']] ?? $templates['H+0'];
            $compiledMessage = $waService->compileTemplate($tmpl, $vars);

            CLI::write("Mengirim [{$sch['label_code']}] ke {$sch['customer_nama']} ({$sch['customer_phone']})...", 'cyan');

            $sendResult = $waService->sendMessage($sch['customer_phone'], $compiledMessage);

            if ($sendResult['status']) {
                $db->table('crm_transaction_followup_labels')
                    ->where('id', $sch['id'])
                    ->update([
                        'status'      => 'sent',
                        'sent_at'     => date('Y-m-d H:i:s'),
                        'message_log' => $compiledMessage,
                        'updated_at'  => date('Y-m-d H:i:s'),
                    ]);
                $sentCount++;
            } else {
                $db->table('crm_transaction_followup_labels')
                    ->where('id', $sch['id'])
                    ->set('retry_count', 'retry_count + 1', false)
                    ->set('message_log', 'Gagal: ' . $sendResult['message'])
                    ->set('updated_at', date('Y-m-d H:i:s'))
                    ->update();
                $failedCount++;
            }

            // Sleep 1 second for rate limit protection
            sleep(1);
        }

        CLI::write("Pengiriman selesai! Berhasil: {$sentCount}, Gagal: {$failedCount}", 'green');
    }
}

