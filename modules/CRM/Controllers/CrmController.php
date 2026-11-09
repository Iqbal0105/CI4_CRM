<?php

declare(strict_types=1);

namespace Modules\CRM\Controllers;

use App\Controllers\BaseController;
use Modules\CRM\Services\WhatsAppGatewayService;

class CrmController extends BaseController
{
    protected WhatsAppGatewayService $waService;

    public function __construct()
    {
        $this->waService = new WhatsAppGatewayService();
    }

    public function index()
    {
        $status = $this->request->getGet('status') ?: 'pending';
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $totalPending = $db->table('crm_transaction_followup_labels')->where('status', 'pending')->countAllResults();
        $overdueCount = $db->table('crm_transaction_followup_labels')->where('status', 'pending')->where('target_date <', $today)->countAllResults();
        $pendingToday = $db->table('crm_transaction_followup_labels')->where('status', 'pending')->where('target_date', $today)->countAllResults();

        $builder = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.nomor_so, so.source, pm.nomor_resi')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left');

        if ($status !== 'all') {
            $builder->where('cfl.status', $status);
        }

        $schedules = $builder->orderBy('cfl.target_date', 'ASC')->limit(150)->get()->getResultArray();

        // Ambil status kirim & ID untuk seluruh label H+ per order_id
        $orderIds = array_values(array_filter(array_unique(array_column($schedules, 'order_id'))));
        $labelsByOrder = [];
        $sentLabelsByOrder = [];
        if (!empty($orderIds)) {
            $labelRows = $db->table('crm_transaction_followup_labels')
                ->select('id, order_id, label_code, status')
                ->whereIn('order_id', $orderIds)
                ->get()
                ->getResultArray();
            foreach ($labelRows as $lr) {
                $labelsByOrder[$lr['order_id']][$lr['label_code']] = [
                    'id'     => $lr['id'],
                    'status' => $lr['status'],
                ];
                if ($lr['status'] === 'sent') {
                    $sentLabelsByOrder[$lr['order_id']][$lr['label_code']] = true;
                }
            }
        }

        $data = [
            'title'             => 'Journal Follow-Up After Sales',
            'schedules'         => $schedules,
            'labelsByOrder'     => $labelsByOrder,
            'sentLabelsByOrder' => $sentLabelsByOrder,
            'status'            => $status,
            'totalPending'      => $totalPending,
            'overdueCount'      => $overdueCount,
            'pendingToday'      => $pendingToday,
        ];

        return view('Modules\CRM\Views\index', $data);
    }

    public function dashboard()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $totalPelanggan = $db->table('pelanggan')->countAllResults();
        $totalVolumeTransaksi = $db->table('sales_order')->where('status !=', 'cancelled')->selectSum('total')->get()->getRow()->total ?? '0.00';

        $repeatCust = $db->query("SELECT COUNT(*) as cnt FROM (SELECT pelanggan_id FROM sales_order WHERE pelanggan_id IS NOT NULL GROUP BY pelanggan_id HAVING COUNT(*) > 1) t")->getRow()->cnt ?? 0;
        $repeatRate = $totalPelanggan > 0 ? round(((int)$repeatCust / (int)$totalPelanggan) * 100, 1) : 0.0;

        $totalPending = $db->table('crm_transaction_followup_labels')->where('status', 'pending')->countAllResults();
        $overdueCount = $db->table('crm_transaction_followup_labels')->where('status', 'pending')->where('target_date <', $today)->countAllResults();
        $pendingToday = $db->table('crm_transaction_followup_labels')->where('status', 'pending')->where('target_date', $today)->countAllResults();
        $broadcastTerkirim = $db->table('crm_transaction_followup_labels')->where('status', 'sent')->countAllResults();

        $topCustomers = $db->table('sales_order so')
            ->select('p.id, p.nama_pelanggan, p.no_telp, MAX(so.source) as source, COUNT(so.id) as total_order, MAX(so.tanggal_so) as last_order')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'inner')
            ->groupBy(['p.id', 'p.nama_pelanggan', 'p.no_telp'])
            ->orderBy('total_order', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $urgentTasks = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.nomor_so, pm.nomor_resi, p.nama_pelanggan, p.no_telp')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->where('cfl.status', 'pending')
            ->orderBy('cfl.target_date', 'ASC')
            ->limit(10)
            ->get()->getResultArray();

        $recentLogs = $db->table('crm_transaction_followup_labels')
            ->where('sent_at IS NOT NULL')
            ->orderBy('sent_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 1. Tren 15 hari terakhir
        $trendLabels = [];
        $trendTarget = [];
        $trendSent   = [];
        for ($i = 14; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $trendLabels[] = date('d M', strtotime($d));
            $trendTarget[] = $db->table('crm_transaction_followup_labels')->where('target_date', $d)->countAllResults();
            $trendSent[]   = $db->table('crm_transaction_followup_labels')->where('sent_at >=', $d . ' 00:00:00')->where('sent_at <=', $d . ' 23:59:59')->countAllResults();
        }

        // 2. Customer per channel
        $channelRows = $db->table('sales_order')
            ->select('source, COUNT(id) as cnt')
            ->where('status !=', 'cancelled')
            ->groupBy('source')
            ->get()->getResultArray();

        $channelLabels = [];
        $channelData   = [];
        foreach ($channelRows as $cr) {
            $channelLabels[] = ucfirst($cr['source'] ?: 'Manual');
            $channelData[]   = (int) $cr['cnt'];
        }
        if (empty($channelLabels)) {
            $channelLabels = ['Toko / POS'];
            $channelData   = [0];
        }

        // 3. Status follow-up per kategori
        $stageLabels = ['H+0', 'H+3', 'H+7', 'H+11', 'H+30', 'H+100'];
        $stagePending = [];
        $stageSent = [];
        foreach ($stageLabels as $sl) {
            $stagePending[] = $db->table('crm_transaction_followup_labels')->where('label_code', $sl)->where('status', 'pending')->countAllResults();
            $stageSent[]    = $db->table('crm_transaction_followup_labels')->where('label_code', $sl)->where('status', 'sent')->countAllResults();
        }

        $data = [
            'title'                => 'Dashboard Performa CRM & After Sales',
            'totalPelanggan'       => $totalPelanggan,
            'totalVolumeTransaksi' => $totalVolumeTransaksi,
            'repeatRate'           => $repeatRate,
            'totalPending'         => $totalPending,
            'overdueCount'         => $overdueCount,
            'pendingToday'         => $pendingToday,
            'broadcastTerkirim'    => $broadcastTerkirim,
            'topCustomers'         => $topCustomers,
            'urgentTasks'          => $urgentTasks,
            'recentLogs'           => $recentLogs,
            'trendLabels'          => $trendLabels,
            'trendTarget'          => $trendTarget,
            'trendSent'            => $trendSent,
            'channelLabels'        => $channelLabels,
            'channelData'          => $channelData,
            'stageLabels'          => $stageLabels,
            'stagePending'         => $stagePending,
            'stageSent'            => $stageSent,
        ];

        return view('Modules\CRM\Views\dashboard', $data);
    }

    protected function getFollowUpTemplates(): array
    {
        $this->ensureFollowupCategoriesTable();
        $db = \Config\Database::connect();
        $catRows = $db->table('crm_followup_categories')->where('status', 'Aktif')->get()->getResultArray();
        if (!empty($catRows)) {
            $templates = [];
            foreach ($catRows as $cr) {
                $dayKey = trim($cr['days']);
                $tpl = str_replace(
                    ['{{nama_customer}}', '{{no_pesanan}}', '{{nomor_resi}}', '{{produk}}', '{{tanggal_kirim}}', '{{sumber_penjualan}}', '{{kurir}}'],
                    ['{nama}', '{nomor_resi}', '{nomor_resi}', '{produk}', '{tanggal_kirim}', '{sumber_penjualan}', '{kurir}'],
                    $cr['template']
                );
                $templates[$dayKey] = $tpl;
                $templates[$cr['code']] = $tpl;
            }
            return $templates;
        }

        return [
            'H+0'   => "Halo kak {nama}, terima kasih telah berbelanja di Metafora! Pesanan Anda #{nomor_resi} ({produk}) sedang disiapkan/dikirim. Pantau perjalanan paket Anda ya!",
            'H+3'   => "Halo kak {nama}, bagaimana kabar paket produk {produk}? Semoga sudah sampai dengan selamat dan sesuai harapan ya. Jika butuh panduan, silakan balas chat ini ya kak!",
            'H+7'   => "Halo kak {nama}, terima kasih telah mempercayai Metafora untuk {produk}! Jangan lupa berikan ulasan terbaik Anda. Gunakan kode promo REPEAT10 untuk diskon belanja berikutnya ya!",
            'H+11'  => "Halo kak {nama}, bagaimana pengalaman menggunakan produk {produk} sejauh ini? Semoga bermanfaat dan memuaskan. Hubungi kami jika ada kendala ya!",
            'H+30'  => "Halo kak {nama}, persediaan {produk} Anda mungkin sudah mulai menipis. Dapatkan penawaran repeat order spesial untuk Anda hari ini!",
            'H+100' => "Halo kak {nama}, apa kabar? Kami merindukan Anda di Metafora! Kunjungi katalog kami untuk melihat promo dan produk terbaru.",
        ];
    }

    public function sendNow(int $id)
    {
        $db = \Config\Database::connect();
        $sch = $db->table('crm_transaction_followup_labels')->where('id', $id)->get()->getRowArray();
        if (!$sch) {
            return redirect()->back()->with('error', 'Jadwal follow-up tidak ditemukan.');
        }

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

        $templates = $this->getFollowUpTemplates();

        $vars = [
            'nama'       => $sch['customer_nama'] ?: 'Pelanggan',
            'nomor_resi' => $order['nomor_resi'] ?? $order['nomor_so'] ?? '-',
            'produk'     => $itemRow['nama_produk'] ?? 'pesanan Anda',
        ];

        $msg = $this->waService->compileTemplate($templates[$sch['label_code']] ?? $templates['H+0'], $vars);
        $result = $this->waService->sendMessage($sch['customer_phone'], $msg);

        if ($result['status']) {
            $this->ensureCrmColumnsExist();
            $userId = session()->get('user')['id'] ?? 1;
            $db->table('crm_transaction_followup_labels')->where('id', $id)->update([
                'status'      => 'sent',
                'sent_at'     => date('Y-m-d H:i:s'),
                'message_log' => $msg,
                'updated_at'  => date('Y-m-d H:i:s'),
                'user_id'     => $userId,
            ]);
            return redirect()->back()->with('success', "Pesan {$sch['label_code']} berhasil dikirimkan ke {$sch['customer_nama']}!");
        }

        return redirect()->back()->with('error', 'Gagal mengirim pesan: ' . $result['message']);
    }

    public function markSent(int $id)
    {
        $this->ensureCrmColumnsExist();
        $db = \Config\Database::connect();
        $userId = session()->get('user')['id'] ?? 1;
        $db->table('crm_transaction_followup_labels')->where('id', $id)->update([
            'status'     => 'sent',
            'sent_at'    => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'user_id'    => $userId,
        ]);

        return $this->response->setJSON(['status' => true]);
    }

    public function chat()
    {
        $label = $this->request->getGet('label');
        $db = \Config\Database::connect();
        $builder = $db->table('crm_followup_notes cfn')
            ->select('cfn.*, p.nama_pelanggan, u.username as cs_nama')
            ->join('pelanggan p', 'p.id = cfn.pelanggan_id', 'left')
            ->join('users u', 'u.id = cfn.user_id', 'left');

        if (!empty($label)) {
            $builder->where('cfn.label_stage', $label);
        }

        $notes = $builder->orderBy('cfn.id', 'DESC')->limit(50)->get()->getResultArray();
        $pelanggan = $db->table('pelanggan')->orderBy('nama_pelanggan', 'ASC')->get()->getResultArray();

        $data = [
            'title'     => '2-Way Chat CRM & Catatan After-Sales',
            'notes'     => $notes,
            'pelanggan' => $pelanggan,
            'label'     => $label,
        ];

        return view('Modules\CRM\Views\chat', $data);
    }

    public function storeNote()
    {
        $rules = [
            'pelanggan_id' => 'required|is_natural_no_zero',
            'label_stage'  => 'required|in_list[H+0,H+3,H+7,H+11,H+30,H+100,general]',
            'notes'        => 'required|min_length[3]',
        ];

        if ($this->request->isAJAX()) {
            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $db = \Config\Database::connect();
            $db->table('crm_followup_notes')->insert([
                'pelanggan_id' => (int) $this->request->getPost('pelanggan_id'),
                'label_stage'  => $this->request->getPost('label_stage'),
                'notes'        => trim((string) $this->request->getPost('notes')),
                'channel'      => $this->request->getPost('channel') ?: 'whatsapp',
                'user_id'      => session()->get('user')['id'] ?? 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Catatan follow-up CRM berhasil disimpan.',
                'note'    => [
                    'label_stage' => $this->request->getPost('label_stage'),
                    'notes'       => trim((string) $this->request->getPost('notes')),
                    'user_name'   => session()->get('user')['username'] ?? 'Developer',
                    'created_at'  => date('d/m/Y H:i'),
                ]
            ]);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->table('crm_followup_notes')->insert([
            'pelanggan_id' => (int) $this->request->getPost('pelanggan_id'),
            'label_stage'  => $this->request->getPost('label_stage'),
            'notes'        => trim((string) $this->request->getPost('notes')),
            'channel'      => $this->request->getPost('channel') ?: 'whatsapp',
            'user_id'      => session()->get('user')['id'] ?? 1,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('crm/chat')->with('success', 'Catatan CRM berhasil disimpan.');
    }

    public function updateLabel(int $id)
    {
        $newLabel = $this->request->getPost('label_stage');
        $db = \Config\Database::connect();
        $db->table('crm_followup_notes')->where('id', $id)->update([
            'label_stage' => $newLabel,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Status label follow-up diperbarui.');
    }

    public function broadcast()
    {
        $db = \Config\Database::connect();

        $logs = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.source, so.nomor_so')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->orderBy('cfl.id', 'DESC')
            ->limit(30)
            ->get()->getResultArray();

        $data = [
            'title' => 'Broadcast WhatsApp Massal',
            'logs'  => $logs,
        ];

        return view('Modules\CRM\Views\broadcast', $data);
    }

    public function filterBroadcast()
    {
        $db = \Config\Database::connect();
        $kategori = $this->request->getGet('kategori_label');
        $sumber = $this->request->getGet('sumber_penjualan');
        $segmen = $this->request->getGet('segmen_rfm');

        $builder = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.nomor_so, so.tanggal_so, so.source, pm.nomor_resi, pm.no_pesanan, p.id as pelanggan_id')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left')
            ->join('pelanggan p', 'p.no_telp = cfl.customer_phone', 'left')
            ->where('cfl.status', 'pending');

        if (!empty($kategori)) {
            $builder->where('cfl.label_code', $kategori);
        }

        if (!empty($sumber)) {
            $builder->where('so.source', $sumber);
        }

        $schedules = $builder->orderBy('cfl.target_date', 'ASC')->limit(100)->get()->getResultArray();

        $templates = $this->getFollowUpTemplates();

        $data = [];
        foreach ($schedules as $sch) {
            $itemRow = $db->table('sales_order_item soi')
                ->select('p.nama_produk')
                ->join('produk p', 'p.id = soi.produk_id', 'left')
                ->where('soi.so_id', $sch['order_id'])
                ->get()
                ->getRowArray();

            $vars = [
                'nama'       => $sch['customer_nama'] ?: 'Pelanggan',
                'nomor_resi' => $sch['nomor_resi'] ?? $sch['nomor_so'] ?? '-',
                'produk'     => $itemRow['nama_produk'] ?? 'pesanan Anda',
            ];

            $previewMsg = $this->waService->compileTemplate($templates[$sch['label_code']] ?? $templates['H+0'], $vars);

            $data[] = [
                'id'             => $sch['id'],
                'customer_nama'  => $sch['customer_nama'] ?: 'Pelanggan',
                'customer_phone' => $sch['customer_phone'],
                'nomor_so'       => $sch['nomor_so'] ?? $sch['no_pesanan'] ?? (string)$sch['order_id'],
                'source'         => $sch['source'] ?: 'toko',
                'label_code'     => $sch['label_code'],
                'target_date'    => !empty($sch['target_date']) ? date('d/m/Y', strtotime($sch['target_date'])) : '-',
                'preview_msg'    => $previewMsg,
            ];
        }

        return $this->response->setJSON([
            'status' => true,
            'total'  => count($data),
            'data'   => $data,
        ]);
    }

    public function sendBroadcast()
    {
        $db = \Config\Database::connect();
        $ids = $this->request->getPost('ids');
        $mode = $this->request->getPost('mode') ?: 'auto';
        $customMessage = trim((string) $this->request->getPost('custom_message'));

        if (empty($ids) || !is_array($ids)) {
            $kategori = $this->request->getPost('kategori_label');
            $sumber = $this->request->getPost('sumber_penjualan');

            $builder = $db->table('crm_transaction_followup_labels cfl')
                ->select('cfl.id')
                ->join('sales_order so', 'so.id = cfl.order_id', 'left')
                ->where('cfl.status', 'pending');
            if (!empty($kategori)) {
                $builder->where('cfl.label_code', $kategori);
            }
            if (!empty($sumber)) {
                $builder->where('so.source', $sumber);
            }
            $fetched = $builder->get()->getResultArray();
            $ids = array_column($fetched, 'id');
        }

        if (empty($ids)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada penerima broadcast yang dipilih.']);
            }
            return redirect()->back()->with('error', 'Tidak ada penerima broadcast yang dipilih.');
        }

        $templates = $this->getFollowUpTemplates();

        $schedules = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.nomor_so, pm.nomor_resi')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left')
            ->whereIn('cfl.id', $ids)
            ->get()
            ->getResultArray();

        $successCount = 0;
        $failedCount = 0;

        foreach ($schedules as $sch) {
            $itemRow = $db->table('sales_order_item soi')
                ->select('p.nama_produk')
                ->join('produk p', 'p.id = soi.produk_id', 'left')
                ->where('soi.so_id', $sch['order_id'])
                ->get()
                ->getRowArray();

            $vars = [
                'nama'       => $sch['customer_nama'] ?: 'Pelanggan',
                'nomor_resi' => $sch['nomor_resi'] ?? $sch['nomor_so'] ?? '-',
                'produk'     => $itemRow['nama_produk'] ?? 'pesanan Anda',
            ];

            if ($mode === 'custom' && !empty($customMessage)) {
                $rawMsg = $customMessage;
            } else {
                $rawMsg = $templates[$sch['label_code']] ?? $templates['H+0'];
            }

            $compiledMsg = $this->waService->compileTemplate($rawMsg, $vars);
            $result = $this->waService->sendMessage($sch['customer_phone'], $compiledMsg);

            if ($result['status']) {
                $this->ensureCrmColumnsExist();
                $userId = session()->get('user')['id'] ?? 1;
                $db->table('crm_transaction_followup_labels')->where('id', $sch['id'])->update([
                    'status'      => 'sent',
                    'sent_at'     => date('Y-m-d H:i:s'),
                    'message_log' => $compiledMsg,
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'user_id'     => $userId,
                ]);
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        $msgText = "Berhasil mengirim broadcast ke {$successCount} pelanggan!";
        if ($failedCount > 0) {
            $msgText .= " ({$failedCount} nomor gagal)";
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'        => $successCount > 0,
                'total'         => count($schedules),
                'success_count' => $successCount,
                'failed_count'  => $failedCount,
                'message'       => $msgText,
            ]);
        }

        return redirect()->to('crm/broadcast')->with('success', $msgText);
    }

    public function broadcastBatch()
    {
        $db = \Config\Database::connect();
        $ids = $this->request->getPost('ids');
        $mode = $this->request->getPost('mode') ?: 'auto';
        $customMessage = trim((string) $this->request->getPost('custom_message'));

        if (empty($ids) || !is_array($ids)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Pilih minimal satu data pelanggan yang akan di-broadcast.',
            ]);
        }

        $templates = $this->getFollowUpTemplates();

        $schedules = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.nomor_so, pm.nomor_resi')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left')
            ->whereIn('cfl.id', $ids)
            ->get()
            ->getResultArray();

        if (empty($schedules)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data jadwal follow-up tidak ditemukan.',
            ]);
        }

        $successCount = 0;
        $failedCount = 0;
        $failedDetails = [];

        foreach ($schedules as $sch) {
            $itemRow = $db->table('sales_order_item soi')
                ->select('p.nama_produk')
                ->join('produk p', 'p.id = soi.produk_id', 'left')
                ->where('soi.so_id', $sch['order_id'])
                ->get()
                ->getRowArray();

            $vars = [
                'nama'       => $sch['customer_nama'] ?: 'Pelanggan',
                'nomor_resi' => $sch['nomor_resi'] ?? $sch['nomor_so'] ?? '-',
                'produk'     => $itemRow['nama_produk'] ?? 'pesanan Anda',
            ];

            if ($mode === 'custom' && !empty($customMessage)) {
                $rawMsg = $customMessage;
            } else {
                $rawMsg = $templates[$sch['label_code']] ?? $templates['H+0'];
            }

            $compiledMsg = $this->waService->compileTemplate($rawMsg, $vars);
            $result = $this->waService->sendMessage($sch['customer_phone'], $compiledMsg);

            if ($result['status']) {
                $this->ensureCrmColumnsExist();
                $userId = session()->get('user')['id'] ?? 1;
                $db->table('crm_transaction_followup_labels')->where('id', $sch['id'])->update([
                    'status'      => 'sent',
                    'sent_at'     => date('Y-m-d H:i:s'),
                    'message_log' => $compiledMsg,
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'user_id'     => $userId,
                ]);
                $successCount++;
            } else {
                $failedCount++;
                $failedDetails[] = "{$sch['customer_nama']} ({$sch['customer_phone']}): " . ($result['message'] ?? 'Gagal');
            }
        }

        $msgText = "Berhasil mengirim broadcast ke {$successCount} pelanggan.";
        if ($failedCount > 0) {
            $msgText .= " ({$failedCount} nomor gagal terkirim)";
        }

        return $this->response->setJSON([
            'status'         => $successCount > 0,
            'total'          => count($schedules),
            'success_count'  => $successCount,
            'failed_count'   => $failedCount,
            'message'        => $msgText,
            'failed_details' => $failedDetails,
        ]);
    }

    public function directory()
    {
        $db = \Config\Database::connect();

        $customers = $db->table('pelanggan p')
            ->select('p.id, p.nama_pelanggan, p.no_telp, p.kode_pelanggan, 
                      COUNT(so.id) as total_order, 
                      MAX(so.tanggal_so) as last_order_date, 
                      MAX(so.source) as last_source,
                      COALESCE(SUM(so.total), 0) as total_spend')
            ->join('sales_order so', 'so.pelanggan_id = p.id AND so.status != "cancelled"', 'left')
            ->where('p.deleted_at IS NULL')
            ->groupBy(['p.id', 'p.nama_pelanggan', 'p.no_telp', 'p.kode_pelanggan'])
            ->orderBy('total_order', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        // Ambil seluruh pending follow-up dari Jurnal Follow-Up (crm_transaction_followup_labels)
        // berserta relasi sales_order.pelanggan_id agar terhubung sinkron 100%
        $pendingLabels = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.id, cfl.order_id, cfl.customer_nama, cfl.customer_phone, so.pelanggan_id')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->where('cfl.status', 'pending')
            ->get()->getResultArray();

        $normalizePhone = static function (?string $phone): string {
            if (!$phone) {
                return '';
            }
            $digits = preg_replace('/\D/', '', $phone);
            if (str_starts_with($digits, '62')) {
                return substr($digits, 2);
            }
            if (str_starts_with($digits, '0')) {
                return substr($digits, 1);
            }
            return $digits;
        };

        // Pre-normalisasi data pending untuk pencocokan cepat dalam memori
        $normalizedPending = [];
        foreach ($pendingLabels as $pl) {
            $normalizedPending[] = [
                'id'           => (int) $pl['id'],
                'pelanggan_id' => !empty($pl['pelanggan_id']) ? (int) $pl['pelanggan_id'] : null,
                'phone_norm'   => $normalizePhone($pl['customer_phone'] ?? ''),
                'name_norm'    => strtolower(trim((string) ($pl['customer_nama'] ?? ''))),
            ];
        }

        // Hitung pending follow-up yang belum selesai untuk setiap customer
        foreach ($customers as &$c) {
            $cId    = (int) $c['id'];
            $cPhone = $normalizePhone($c['no_telp'] ?? '');
            $cName  = strtolower(trim((string) ($c['nama_pelanggan'] ?? '')));

            $matchedLabelIds = [];

            foreach ($normalizedPending as $npl) {
                $matched = false;

                // 1. Relasi langsung via sales_order.pelanggan_id
                if ($npl['pelanggan_id'] !== null && $npl['pelanggan_id'] === $cId) {
                    $matched = true;
                }

                // 2. Pencocokan nomor HP ternormalisasi (minimal 7 digit untuk akurasi)
                if (!$matched && !empty($cPhone) && strlen($cPhone) >= 7) {
                    if (!empty($npl['phone_norm']) && (
                        $npl['phone_norm'] === $cPhone ||
                        str_ends_with($npl['phone_norm'], $cPhone) ||
                        str_ends_with($cPhone, $npl['phone_norm'])
                    )) {
                        $matched = true;
                    }
                }

                // 3. Pencocokan nama pelanggan (jika nama spesifik, min 3 karakter)
                if (!$matched && !empty($cName) && mb_strlen($cName) >= 3) {
                    if ($npl['name_norm'] === $cName) {
                        $matched = true;
                    }
                }

                if ($matched) {
                    $matchedLabelIds[$npl['id']] = true;
                }
            }

            $c['pending_count'] = count($matchedLabelIds);

            // Simple segment classification for display
            $orders = (int) $c['total_order'];
            if ($orders >= 3) {
                $c['segment'] = 'Champions';
                $c['score'] = '5-5-5';
            } elseif ($orders === 2) {
                $c['segment'] = 'Loyal Customers';
                $c['score'] = '4-5-4';
            } else {
                $c['segment'] = 'Need Attention';
                $c['score'] = '3-2-1';
            }
        }
        unset($c);

        $data = [
            'title'     => 'Customer Directory CRM',
            'customers' => $customers,
        ];

        return view('Modules\CRM\Views\directory', $data);
    }

    /**
     * AJAX endpoint: Detail Pelanggan dengan integrasi metrik CRM & RFM lengkap.
     */
    public function customerDetail($id)
    {
        $id = (int) $id;
        $db = \Config\Database::connect();

        $customer = $db->table('pelanggan p')
            ->select('p.*')
            ->where('p.id', $id)
            ->where('p.deleted_at IS NULL')
            ->get()->getRowArray();

        if (!$customer) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data pelanggan tidak ditemukan',
            ])->setStatusCode(404);
        }

        // 1. Sales Orders
        $salesOrders = [];
        if ($db->tableExists('sales_order')) {
            $salesOrders = $db->table('sales_order so')
                ->select('so.id, so.nomor_so, so.tanggal_so, so.total, so.status, so.source, so.metode_bayar')
                ->where('so.pelanggan_id', $id)
                ->where('so.status !=', 'cancelled')
                ->orderBy('so.tanggal_so', 'DESC')
                ->orderBy('so.id', 'DESC')
                ->limit(10)
                ->get()->getResultArray();
        }

        $totalOrder = count($salesOrders);
        $totalSpend = 0.0;
        $lastOrderDate = null;
        $lastSource = 'Toko';

        if ($totalOrder > 0) {
            $totalSpend = (float) array_sum(array_column($salesOrders, 'total'));
            $lastOrderDate = $salesOrders[0]['tanggal_so'] ?? null;
            $lastSource = $salesOrders[0]['source'] ?? 'Toko';
        }

        // Recency in days
        $recencyDays = 999;
        if (!empty($lastOrderDate)) {
            try {
                $lastDt = new \DateTime($lastOrderDate);
                $nowDt  = new \DateTime();
                $recencyDays = max(0, $nowDt->diff($lastDt)->days);
            } catch (\Throwable $e) {
                $recencyDays = 0;
            }
        }

        // Skor R
        $rScore = match (true) {
            $recencyDays <= 7  => 5,
            $recencyDays <= 14 => 4,
            $recencyDays <= 30 => 3,
            $recencyDays <= 60 => 2,
            default            => 1,
        };

        // Skor F
        $fScore = match (true) {
            $totalOrder >= 5 => 5,
            $totalOrder >= 4 => 4,
            $totalOrder >= 3 => 3,
            $totalOrder >= 2 => 2,
            default          => 1,
        };

        // Skor M
        $mScore = match (true) {
            $totalSpend >= 5000000 => 5,
            $totalSpend >= 2000000 => 4,
            $totalSpend >= 1000000 => 3,
            $totalSpend >= 500000  => 2,
            default                => 1,
        };

        // Standard 10 Segments
        $segment = 'Need Attention';
        if ($rScore >= 4 && $fScore >= 4 && $mScore >= 4) {
            $segment = 'Champions';
        } elseif ($rScore <= 2 && $fScore >= 4 && $mScore >= 4) {
            $segment = "Can't Lose Them";
        } elseif ($rScore >= 3 && $fScore >= 3 && $mScore >= 3) {
            $segment = 'Loyal Customers';
        } elseif ($mScore >= 4 && $rScore >= 3) {
            $segment = 'Big Spenders';
        } elseif ($rScore >= 4 && $fScore >= 2) {
            $segment = 'Potential Loyalist';
        } elseif ($rScore >= 4 && $fScore == 1) {
            $segment = 'New Customers';
        } elseif ($fScore >= 3 && $mScore <= 2) {
            $segment = 'Frequent Low Value';
        } elseif ($rScore <= 2 && $fScore >= 3) {
            $segment = 'At Risk Customers';
        } elseif ($rScore <= 2 && $fScore <= 2 && $mScore <= 2) {
            $segment = 'Hibernating';
        }

        // Fetch Goal & Action from rfm_segments
        $segmentGoal = 'Kenalkan kembali brand & manfaat produk';
        $segmentAction = 'Kirim ulang value produk + testimoni relevan';

        if ($db->tableExists('rfm_segments')) {
            $aliases = [$segment];
            if ($segment === 'Potential Loyalist') $aliases[] = 'Potential Loyalists';
            if ($segment === 'At Risk Customers') $aliases[] = 'At Risk';

            $segRow = $db->table('rfm_segments')->whereIn('segment_name', $aliases)->get()->getRowArray();
            if ($segRow) {
                if (!empty($segRow['description'])) $segmentGoal = $segRow['description'];
                if (!empty($segRow['recommended_action'])) $segmentAction = $segRow['recommended_action'];
            }
        }

        // 2. CRM Follow-Up Labels & Tasks
        $orderIds = array_column($salesOrders, 'id');
        $followupLabels = [];
        if ($db->tableExists('crm_transaction_followup_labels')) {
            $fb = $db->table('crm_transaction_followup_labels cfl')
                ->select('cfl.*, so.nomor_so, pm.nomor_resi, pm.kurir as pm_kurir')
                ->join('sales_order so', 'so.id = cfl.order_id', 'left')
                ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left');

            $phoneClean = preg_replace('/\D/', '', (string)($customer['no_telp'] ?? ''));
            $phoneSuffix = strlen($phoneClean) >= 7 ? substr($phoneClean, -7) : $phoneClean;

            if (!empty($orderIds)) {
                $fb->groupStart()->whereIn('cfl.order_id', $orderIds);
                if (!empty($phoneSuffix)) {
                    $fb->orLike('cfl.customer_phone', $phoneSuffix);
                }
                $fb->groupEnd();
            } elseif (!empty($phoneSuffix)) {
                $fb->like('cfl.customer_phone', $phoneSuffix);
            } else {
                $fb->where('cfl.customer_nama', $customer['nama_pelanggan']);
            }

            $rawLabels = $fb->orderBy('cfl.target_date', 'DESC')->limit(15)->get()->getResultArray();
            foreach ($rawLabels as $rl) {
                $lblCode = $rl['label_code'] ?? 'H+0';
                $lblDesc = match($lblCode) {
                    'H+0' => 'Konfirmasi Pesanan',
                    'H+3' => 'Cek Penerimaan Produk',
                    'H+7' => 'Ulasan & Kepuasan Pelanggan',
                    default => 'Follow-Up Pesanan',
                };
                $rl['label_name'] = $lblDesc;
                $rl['tracking_number'] = $rl['nomor_resi'] ?? '-';
                $rl['courier'] = $rl['pm_kurir'] ?? '-';
                $rl['product_name'] = 'Produk Pesanan';
                $followupLabels[] = $rl;
            }
        }

        $pendingCount = 0;
        $doneCount = 0;
        foreach ($followupLabels as $fl) {
            if ($fl['status'] === 'pending') $pendingCount++;
            else $doneCount++;
        }

        // 3. CRM Notes / Interaction History
        $notes = [];
        if ($db->tableExists('crm_followup_notes')) {
            $notes = $db->table('crm_followup_notes cfn')
                ->select('cfn.*, u.username as cs_name')
                ->join('users u', 'u.id = cfn.user_id', 'left')
                ->where('cfn.pelanggan_id', $id)
                ->orderBy('cfn.created_at', 'DESC')
                ->limit(10)
                ->get()->getResultArray();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'customer' => [
                    'id'              => (int) $customer['id'],
                    'kode_pelanggan'  => $customer['kode_pelanggan'] ?? 'CUST-' . str_pad((string)$customer['id'], 4, '0', STR_PAD_LEFT),
                    'nama_pelanggan'  => $customer['nama_pelanggan'],
                    'no_telp'         => $customer['no_telp'] ?? '-',
                    'email'           => $customer['email'] ?? '-',
                    'kota'            => $customer['kota'] ?? '-',
                    'alamat'          => $customer['alamat'] ?? '-',
                    'tipe'            => $customer['tipe'] ?? 'Regular',
                    'last_source'     => $lastSource,
                ],
                'rfm' => [
                    'segment'         => $segment,
                    'score_r'         => $rScore,
                    'score_f'         => $fScore,
                    'score_m'         => $mScore,
                    'score_rfm'       => "{$rScore}-{$fScore}-{$mScore}",
                    'recency_days'    => $recencyDays,
                    'last_order_date' => !empty($lastOrderDate) ? date('d M Y', strtotime($lastOrderDate)) : '-',
                    'total_orders'    => $totalOrder,
                    'total_spend'     => $totalSpend,
                    'total_spend_fmt' => 'Rp ' . number_format($totalSpend, 0, ',', '.'),
                    'aov'             => $totalOrder > 0 ? round($totalSpend / $totalOrder) : 0,
                    'aov_fmt'         => 'Rp ' . number_format($totalOrder > 0 ? round($totalSpend / $totalOrder) : 0, 0, ',', '.'),
                    'goal'            => $segmentGoal,
                    'action'          => $segmentAction,
                ],
                'crm' => [
                    'pending_count'   => $pendingCount,
                    'done_count'      => $doneCount,
                    'followup_labels' => $followupLabels,
                    'notes'           => $notes,
                    'sales_orders'    => $salesOrders,
                ],
            ],
        ]);
    }

    /**
     * Dedicated View Page: Detail Customer CRM & RFM (sesuai foto referensi).
     */
    public function customerDetailView()
    {
        $id    = (int) $this->request->getGet('id');
        $phone = trim((string) $this->request->getGet('phone'));
        $name  = trim((string) $this->request->getGet('name'));
        $db    = \Config\Database::connect();

        $customer = null;
        if ($id > 0) {
            $customer = $db->table('pelanggan p')
                ->where('p.id', $id)
                ->where('p.deleted_at IS NULL')
                ->get()->getRowArray();
        }

        if (!$customer && !empty($phone)) {
            $phoneClean = preg_replace('/\D/', '', $phone);
            $phoneSuffix = strlen($phoneClean) >= 8 ? substr($phoneClean, -8) : $phoneClean;

            $custBuilder = $db->table('pelanggan')->where('deleted_at IS NULL');
            if (!empty($phoneSuffix)) {
                $custBuilder->like('no_telp', $phoneSuffix);
            } else {
                $custBuilder->where('no_telp', $phone);
            }
            $customer = $custBuilder->get()->getRowArray();

            if (!$customer && !empty($phoneSuffix) && $db->tableExists('sales_order')) {
                $soMatch = $db->table('sales_order')
                    ->select('pelanggan_id, nama_penerima, no_telepon')
                    ->like('no_telepon', $phoneSuffix)
                    ->where('status !=', 'cancelled')
                    ->orderBy('id', 'DESC')
                    ->limit(1)
                    ->get()->getRowArray();
                if ($soMatch && !empty($soMatch['pelanggan_id'])) {
                    $customer = $db->table('pelanggan')->where('id', $soMatch['pelanggan_id'])->get()->getRowArray();
                }
                if (!$customer && $soMatch) {
                    $customer = [
                        'id'             => 0,
                        'nama_pelanggan' => $soMatch['nama_penerima'] ?: 'Customer',
                        'no_telp'        => $soMatch['no_telepon'] ?: $phone,
                    ];
                }
            }
        }

        if (!$customer && !empty($name)) {
            $customer = $db->table('pelanggan')
                ->where('deleted_at IS NULL')
                ->like('nama_pelanggan', $name)
                ->get()->getRowArray();
        }

        if (!$customer) {
            // Prioritaskan customer aktual terdaftar (bukan akun walk-in umum)
            $customer = $db->table('pelanggan')
                ->where('deleted_at IS NULL')
                ->where('nama_pelanggan NOT LIKE', '%Walk-in%')
                ->where('nama_pelanggan NOT LIKE', '%Pelanggan Umum%')
                ->orderBy('id', 'ASC')
                ->limit(1)
                ->get()->getRowArray();
        }

        if (!$customer) {
            $customer = $db->table('pelanggan')->where('deleted_at IS NULL')->orderBy('id', 'ASC')->limit(1)->get()->getRowArray();
        }

        $customerId    = (int) ($customer['id'] ?? 0);
        $customerNama  = $customer['nama_pelanggan'] ?? 'Customer';
        $customerPhone = $customer['no_telp'] ?? '-';

        // Ambil orders
        $orders = [];
        if ($db->tableExists('sales_order')) {
            $ob = $db->table('sales_order so')
                ->select('so.*, pm.nomor_resi as pm_resi, pm.no_pesanan as pm_no_pesanan, pm.kurir as pm_kurir')
                ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left')
                ->where('so.status !=', 'cancelled');

            if ($customerId > 0) {
                $ob->where('so.pelanggan_id', $customerId);
            } elseif (!empty($customerPhone) && $customerPhone !== '-') {
                $pClean = preg_replace('/\D/', '', $customerPhone);
                $pSuffix = strlen($pClean) >= 8 ? substr($pClean, -8) : $pClean;
                $ob->like('so.no_telepon', $pSuffix);
            }
            $orders = $ob->orderBy('so.tanggal_so', 'DESC')->orderBy('so.id', 'DESC')->get()->getResultArray();
        }

        $totalOrders       = count($orders);
        $totalSpend        = 0.0;
        $orderCountToko    = 0;
        $orderCountOnline  = 0;
        $firstOrder        = null;
        $lastOrder         = null;
        $sourcesMap        = [];

        foreach ($orders as $ord) {
            $totalSpend += (float) ($ord['total'] ?? 0);
            $src = strtolower(trim((string)($ord['source'] ?? '')));
            if (empty($src) || in_array($src, ['toko', 'offline', 'pos', 'store', 'toko fisik'])) {
                $orderCountToko++;
                $sourcesMap['Toko'] = 'Toko';
            } else {
                $orderCountOnline++;
                $srcName = ucfirst($src);
                if (stripos($src, 'shopee') !== false) $srcName = 'Shopee';
                elseif (stripos($src, 'tokopedia') !== false) $srcName = 'Tokopedia';
                elseif (stripos($src, 'tiktok') !== false) $srcName = 'TikTok';
                $sourcesMap[$srcName] = $srcName;
            }

            $tgl = $ord['tanggal_so'] ?? null;
            if ($tgl) {
                if ($firstOrder === null || $tgl < $firstOrder) $firstOrder = $tgl;
                if ($lastOrder === null || $tgl > $lastOrder) $lastOrder = $tgl;
            }
        }
        if (empty($sourcesMap)) {
            $sourcesMap = ['Toko' => 'Toko'];
        }

        // Hitung Recency Days
        $daysSinceLast = 0;
        if (!empty($lastOrder)) {
            try {
                $lastDt = new \DateTime($lastOrder);
                $nowDt  = new \DateTime();
                $daysSinceLast = max(0, $nowDt->diff($lastDt)->days);
            } catch (\Throwable $e) {
                $daysSinceLast = 0;
            }
        }

        // Gunakan RfmAnalysisService jika tersedia untuk konsistensi segment
        $rScore = 5; $fScore = 5; $mScore = 5;
        $segment = 'Champions';

        try {
            $rfmService = new \Modules\RFM\Services\RfmAnalysisService($db);
            $rfmData = $rfmService->calculateRfm(365);
            $foundCust = null;
            foreach ($rfmData['customers'] as $rc) {
                if ($customerId > 0 && (int)$rc['id'] === $customerId) {
                    $foundCust = $rc;
                    break;
                }
            }
            if ($foundCust) {
                $rScore  = (int) $foundCust['r_score'];
                $fScore  = (int) $foundCust['f_score'];
                $mScore  = (int) $foundCust['m_score'];
                $segment = $foundCust['segment'];
                $daysSinceLast = (int) $foundCust['recency_days'];
            } else {
                // Fallback scoring
                $rScore = match (true) {
                    $daysSinceLast <= 15 => 5,
                    $daysSinceLast <= 30 => 4,
                    $daysSinceLast <= 60 => 3,
                    $daysSinceLast <= 120 => 2,
                    default               => 1,
                };
                $fScore = match (true) {
                    $totalOrders >= 5 => 5,
                    $totalOrders >= 4 => 4,
                    $totalOrders >= 3 => 3,
                    $totalOrders >= 2 => 2,
                    default           => 1,
                };
                $mScore = match (true) {
                    $totalSpend >= 5000000 => 5,
                    $totalSpend >= 2000000 => 4,
                    $totalSpend >= 1000000 => 3,
                    $totalSpend >= 500000  => 2,
                    default                => 1,
                };
                if ($rScore >= 4 && $fScore >= 4 && $mScore >= 4) $segment = 'Champions';
                elseif ($rScore >= 3 && $fScore >= 3 && $mScore >= 3) $segment = 'Loyal Customers';
                elseif ($rScore <= 2 && $fScore >= 4 && $mScore >= 4) $segment = "Can't Lose Them";
                elseif ($mScore >= 4 && $rScore >= 3) $segment = 'Big Spenders';
                elseif ($rScore >= 4 && $fScore >= 2) $segment = 'Potential Loyalist';
                elseif ($rScore >= 4 && $fScore == 1) $segment = 'New Customers';
                elseif ($fScore >= 3 && $mScore <= 2) $segment = 'Frequent Low Value';
                elseif ($rScore <= 2 && $fScore >= 2) $segment = 'At Risk Customers';
                elseif ($rScore <= 2 && $fScore == 1) $segment = 'Hibernating';
                else $segment = 'Need Attention';
            }
        } catch (\Throwable $e) {
            // fallback
        }

        // Labels
        $rLabel = match($rScore) {
            5 => 'Sangat baru',
            4 => 'Baru',
            3 => 'Cukup lama',
            2 => 'Lama',
            default => 'Sangat lama',
        };
        $fLabel = match($fScore) {
            5 => 'Sangat sering',
            4 => 'Sering',
            3 => 'Kadang',
            2 => 'Jarang',
            default => 'Sangat jarang',
        };
        $mLabel = match($mScore) {
            5 => 'Sangat Besar',
            4 => 'Besar',
            3 => 'Menengah',
            2 => 'Kecil',
            default => 'Sangat Kecil',
        };

        // Ambil strategi Goal & Action dari tabel rfm_segments
        $goal   = 'Pertahankan loyalitas dan beri apresiasi';
        $action = 'Berikan hadiah eksklusif, early access, dan testimoni program';
        if ($db->tableExists('rfm_segments')) {
            $stratRow = $db->table('rfm_segments')->like('segment_name', $segment)->get()->getRowArray();
            if ($stratRow) {
                if (!empty($stratRow['description'])) $goal = $stratRow['description'];
                if (!empty($stratRow['recommended_action'])) $action = $stratRow['recommended_action'];
            }
        }

        $badgeStyles = [
            'Champions'          => ['bg' => '#fef3c7', 'color' => '#b45309', 'border' => '#fde68a', 'icon' => 'fa-trophy'],
            'Loyal Customers'    => ['bg' => '#dcfce7', 'color' => '#166534', 'border' => '#bbf7d0', 'icon' => 'fa-gem'],
            'Potential Loyalist' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'border' => '#c7d2fe', 'icon' => 'fa-star'],
            'New Customers'      => ['bg' => '#e0f2fe', 'color' => '#075985', 'border' => '#bae6fd', 'icon' => 'fa-user-plus'],
            'Big Spenders'       => ['bg' => '#f3e8ff', 'color' => '#6b21a8', 'border' => '#e9d5ff', 'icon' => 'fa-credit-card'],
            'Frequent Low Value' => ['bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#e2e8f0', 'icon' => 'fa-basket-shopping'],
            'Need Attention'     => ['bg' => '#ffedd5', 'color' => '#9a3412', 'border' => '#fed7aa', 'icon' => 'fa-bell'],
            'At Risk Customers'  => ['bg' => '#fee2e2', 'color' => '#991b1b', 'border' => '#fca5a5', 'icon' => 'fa-triangle-exclamation'],
            "Can't Lose Them"    => ['bg' => '#fce7f3', 'color' => '#9d174d', 'border' => '#fbcfe8', 'icon' => 'fa-heart'],
            'Hibernating'        => ['bg' => '#f3f4f6', 'color' => '#1f2937', 'border' => '#e5e7eb', 'icon' => 'fa-moon'],
        ];
        $badge = $badgeStyles[$segment] ?? ['bg' => '#fef3c7', 'color' => '#b45309', 'border' => '#fde68a', 'icon' => 'fa-trophy'];

        $summary = [
            'id'                 => $customerId,
            'nama'               => $customerNama,
            'phone'              => $customerPhone,
            'total_orders'       => $totalOrders,
            'total_spend'        => $totalSpend,
            'order_count_toko'   => $orderCountToko,
            'order_count_online' => $orderCountOnline,
            'first_order'        => $firstOrder,
            'last_order'         => $lastOrder,
            'sources'            => array_values($sourcesMap),
            'rfm'                => [
                'r_score'         => $rScore,
                'f_score'         => $fScore,
                'm_score'         => $mScore,
                'r_label'         => $rLabel,
                'f_label'         => $fLabel,
                'm_label'         => $mLabel,
                'rfm_display'     => "{$rScore}-{$fScore}-{$mScore}",
                'segment'         => $segment,
                'goal'            => $goal,
                'action'          => $action,
                'badge'           => $badge,
                'days_since_last' => $daysSinceLast,
            ],
        ];

        // Analisis RFM Per Produk
        $productRfm = [];
        $orderIds = array_column($orders, 'id');
        if (!empty($orderIds) && $db->tableExists('sales_order_item')) {
            $productRfm = $db->table('sales_order_item soi')
                ->select('
                    soi.produk_id,
                    COALESCE(NULLIF(p.nama_produk, ""), "Produk") as nama_produk,
                    COALESCE(NULLIF(p.kode_produk, ""), "-") as kode_produk,
                    MAX(so.tanggal_so) as last_bought_date,
                    DATEDIFF(CURRENT_DATE(), MAX(so.tanggal_so)) as recency_days,
                    COUNT(DISTINCT so.id) as order_count,
                    SUM(soi.qty) as total_qty,
                    SUM(soi.subtotal) as total_spend
                ')
                ->join('sales_order so', 'so.id = soi.so_id')
                ->join('produk p', 'p.id = soi.produk_id', 'left')
                ->whereIn('soi.so_id', $orderIds)
                ->where('so.status !=', 'cancelled')
                ->groupBy(['soi.produk_id', 'nama_produk', 'kode_produk'])
                ->orderBy('total_spend', 'DESC')
                ->get()->getResult();
        }

        // Riwayat Transaksi
        $transactions = [];
        foreach ($orders as $ord) {
            $itemNames = [];
            if ($db->tableExists('sales_order_item')) {
                $itemsRow = $db->table('sales_order_item soi')
                    ->select('COALESCE(NULLIF(p.nama_produk, ""), "Produk") as nama_produk')
                    ->join('produk p', 'p.id = soi.produk_id', 'left')
                    ->where('soi.so_id', $ord['id'])
                    ->get()->getResultArray();
                $itemNames = array_column($itemsRow, 'nama_produk');
            }
            $ringkasan = !empty($itemNames) ? implode(', ', $itemNames) : '-';

            $transactions[] = (object) [
                'id'                => $ord['id'],
                'tbl'               => 'sales_order',
                'tanggal'           => $ord['tanggal_so'],
                'no_pesanan'        => $ord['pm_no_pesanan'] ?? $ord['nomor_so'],
                'nomor_so'          => $ord['nomor_so'],
                'sumber_penjualan'  => !empty($ord['source']) ? ucfirst($ord['source']) : 'Toko',
                'produk_ringkasan'  => $ringkasan,
                'total'             => (float) $ord['total'],
                'status'            => $ord['status'],
                'status_pesanan'    => $ord['status'],
                'nomor_resi'        => $ord['pm_resi'] ?? $ord['nomor_resi'] ?? '-',
                'nama_penerima'     => $ord['nama_penerima'] ?? $customerNama,
                'alamat_pengiriman' => $ord['alamat_pengiriman'] ?? '-',
                'kurir_ekspedisi'   => $ord['pm_kurir'] ?? $ord['kurir'] ?? '-',
            ];
        }

        // Ambil data Follow-Up CRM Pelanggan
        $followupLabels = [];
        if ($db->tableExists('crm_transaction_followup_labels')) {
            $fb = $db->table('crm_transaction_followup_labels cfl')
                ->select('cfl.*, so.nomor_so, pm.nomor_resi, pm.kurir as pm_kurir')
                ->join('sales_order so', 'so.id = cfl.order_id', 'left')
                ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left');

            $phoneClean = preg_replace('/\D/', '', (string)($customer['no_telp'] ?? ''));
            $phoneSuffix = strlen($phoneClean) >= 7 ? substr($phoneClean, -7) : $phoneClean;

            if (!empty($orderIds)) {
                $fb->groupStart()->whereIn('cfl.order_id', $orderIds);
                if (!empty($phoneSuffix)) {
                    $fb->orLike('cfl.customer_phone', $phoneSuffix);
                }
                $fb->groupEnd();
            } elseif (!empty($phoneSuffix)) {
                $fb->like('cfl.customer_phone', $phoneSuffix);
            } else {
                $fb->where('cfl.customer_nama', $customerNama);
            }

            $rawLabels = $fb->orderBy('cfl.target_date', 'DESC')->limit(15)->get()->getResultArray();
            foreach ($rawLabels as $rl) {
                $lblCode = $rl['label_code'] ?? 'H+0';
                $lblDesc = match($lblCode) {
                    'H+0' => 'Konfirmasi Pesanan',
                    'H+3' => 'Cek Penerimaan Produk',
                    'H+7' => 'Ulasan & Kepuasan Pelanggan',
                    default => 'Follow-Up Pesanan',
                };
                $orderId = $rl['order_id'];
                $prodName = 'Produk Pesanan';
                foreach ($transactions as $trx) {
                    if ($trx->id == $orderId && !empty($trx->produk_ringkasan) && $trx->produk_ringkasan !== '-') {
                        $prodName = $trx->produk_ringkasan;
                        break;
                    }
                }
                $rl['label_name'] = $lblDesc;
                $rl['tracking_number'] = $rl['nomor_resi'] ?? '-';
                $rl['courier'] = $rl['pm_kurir'] ?? '-';
                $rl['product_name'] = $prodName;
                $followupLabels[] = $rl;
            }
        }

        $pendingCount = 0;
        $doneCount = 0;
        foreach ($followupLabels as $fl) {
            if ($fl['status'] === 'pending') $pendingCount++;
            else $doneCount++;
        }

        // Riwayat Catatan CRM
        $notes = [];
        if ($db->tableExists('crm_followup_notes')) {
            $notes = $db->table('crm_followup_notes cfn')
                ->select('cfn.*, u.username as cs_name')
                ->join('users u', 'u.id = cfn.user_id', 'left')
                ->where('cfn.pelanggan_id', $customerId)
                ->orderBy('cfn.created_at', 'DESC')
                ->limit(10)
                ->get()->getResultArray();
        }

        return view('Modules\CRM\Views\customer_detail', [
            'title'          => 'Detail Pelanggan CRM - ' . $customerNama,
            'customer'       => $customer,
            'summary'        => $summary,
            'product_rfm'    => $productRfm,
            'transactions'   => $transactions,
            'followupLabels' => $followupLabels,
            'notes'          => $notes,
            'pendingCount'   => $pendingCount,
            'doneCount'      => $doneCount,
        ]);
    }

    /**
     * AJAX endpoint: Detail item & timeline follow-up untuk expand row riwayat transaksi.
     */
    public function transactionDetailAjax()
    {
        $table   = $this->request->getGet('table') ?: 'sales_order';
        $orderId = (int) $this->request->getGet('order_id');
        $db      = \Config\Database::connect();

        $items = [];
        if ($db->tableExists('sales_order_item')) {
            $items = $db->table('sales_order_item soi')
                ->select('soi.*, COALESCE(NULLIF(p.nama_produk, ""), "Produk") as nama_produk, p.kode_produk, p.satuan')
                ->join('produk p', 'p.id = soi.produk_id', 'left')
                ->where('soi.so_id', $orderId)
                ->get()->getResultArray();
        }

        $timeline = [];
        if ($db->tableExists('crm_transaction_followup_labels')) {
            $labels = $db->table('crm_transaction_followup_labels cfl')
                ->select('cfl.*, fc.nama_kategori, fc.jumlah_hari')
                ->join('crm_followup_categories fc', 'fc.id = cfl.followup_category_id', 'left')
                ->where('cfl.order_id', $orderId)
                ->orderBy('cfl.target_date', 'ASC')
                ->get()->getResultArray();

            foreach ($labels as $lbl) {
                $notes = '';
                if ($db->tableExists('crm_followup_notes')) {
                    $noteRows = $db->table('crm_followup_notes')
                        ->where('followup_label_id', $lbl['id'])
                        ->get()->getResultArray();
                    $notes = implode(' | ', array_column($noteRows, 'catatan'));
                }
                $catName = $lbl['nama_kategori'] ?? $lbl['label_name'] ?? $lbl['label_code'] ?? 'Follow-Up';
                $days = $lbl['jumlah_hari'] ?? 0;
                $statusLabel = ($lbl['status'] === 'sent') ? 'Selesai' : 'Pending';

                $timeline[] = [
                    'nama_kategori'        => $catName,
                    'jumlah_hari'          => $days,
                    'tanggal_label_muncul' => !empty($lbl['target_date']) ? date('d/m/Y', strtotime($lbl['target_date'])) : '-',
                    'status_label'         => $statusLabel,
                    'broadcast_count'      => ($lbl['status'] === 'sent') ? 1 : 0,
                    'notes'                => $notes,
                ];
            }
        }

        return $this->response->setJSON([
            'success'  => true,
            'items'    => $items,
            'timeline' => $timeline,
        ]);
    }

    private function ensureCrmColumnsExist(): void
    {
        $db = \Config\Database::connect();
        if ($db->tableExists('crm_transaction_followup_labels')) {
            $fields = $db->getFieldNames('crm_transaction_followup_labels');
            if (!in_array('user_id', $fields)) {
                $forge = \Config\Database::forge();
                $forge->addColumn('crm_transaction_followup_labels', [
                    'user_id' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'unsigned'   => true,
                        'null'       => true,
                        'default'    => null,
                        'after'      => 'order_id',
                    ],
                ]);
            }
            // Update historical sent records without user_id to user_id = 1 (admin)
            $db->query("UPDATE crm_transaction_followup_labels SET user_id = 1 WHERE status = 'sent' AND (user_id IS NULL OR user_id = 0)");
        }
    }

    public function kinerja()
    {
        $this->ensureCrmColumnsExist();
        $db = \Config\Database::connect();

        $startDate      = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate        = $this->request->getGet('end_date') ?: date('Y-m-d');
        $selectedUserId = (int) ($this->request->getGet('user_id') ?: 0);
        $currentUserId  = session()->get('user')['id'] ?? 1;

        // Ambil seluruh user aktif untuk opsi filter
        $allCsUsers = $db->table('users u')
            ->select('u.id, u.username, u.email, r.name as role_name')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.status', 'active')
            ->orderBy('u.username', 'ASC')
            ->get()->getResultArray();

        // Query user CS yang akan dianalisis
        $userBuilder = $db->table('users u')
            ->select('u.id, u.username, u.email, r.name as role_name')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.status', 'active');

        if ($selectedUserId > 0) {
            $userBuilder->where('u.id', $selectedUserId);
        }

        $csUsers = $userBuilder->orderBy('u.id', 'ASC')->limit(20)->get()->getResultArray();

        // Cari ID pesanan yang benar-benar berstatus Repeat Order (bukan pesanan perdana / pertama).
        // Sebuah pesanan adalah Repeat Order jika pelanggan tersebut sudah pernah memesan sebelumnya.
        $repeatOrderIds = [];
        if ($db->tableExists('sales_order')) {
            $repOrdersQuery = $db->query("
                SELECT so.id
                FROM sales_order so
                WHERE so.status IN ('confirmed', 'packing', 'ready_to_ship', 'shipped', 'delivered', 'completed')
                  AND so.pelanggan_id IS NOT NULL
                  AND EXISTS (
                      SELECT 1 FROM sales_order so_prev
                      WHERE so_prev.pelanggan_id = so.pelanggan_id
                        AND so_prev.status IN ('confirmed', 'packing', 'ready_to_ship', 'shipped', 'delivered', 'completed')
                        AND (so_prev.tanggal_so < so.tanggal_so OR (so_prev.tanggal_so = so.tanggal_so AND so_prev.id < so.id))
                  )
            ");
            if ($repOrdersQuery) {
                $repRows = $repOrdersQuery->getResultArray();
                $repeatOrderIds = array_column($repRows, 'id');
            }
        }

        $csList          = [];
        $totalHandledAll = 0;
        $totalSentAll    = 0;
        $totalOmsetAll   = 0;
        $totalConvSum    = 0;

        foreach ($csUsers as $u) {
            // 1. Handling Count per CS
            $handlingQuery = $db->table('crm_followup_notes')->where('user_id', $u['id']);
            if (!empty($startDate)) {
                $handlingQuery->where('created_at >=', $startDate . ' 00:00:00');
            }
            if (!empty($endDate)) {
                $handlingQuery->where('created_at <=', $endDate . ' 23:59:59');
            }
            $handlingCount = $handlingQuery->countAllResults();

            // 2. WA Sent per CS (SPESIFIK PER AKUN CS)
            $sentQuery = $db->table('crm_transaction_followup_labels')
                ->where('status', 'sent')
                ->where('user_id', $u['id']);
            if (!empty($startDate)) {
                $sentQuery->where('sent_at >=', $startDate . ' 00:00:00');
            }
            if (!empty($endDate)) {
                $sentQuery->where('sent_at <=', $endDate . ' 23:59:59');
            }
            $sentCount = $sentQuery->countAllResults();

            // 3. Omset Repeat Order per CS (Hanya pesanan yang benar-benar Repeat Order)
            $omsetRepeat      = 0;
            $repeatOrderCount = 0;
            if (!empty($repeatOrderIds)) {
                $orderQuery = $db->table('sales_order')
                    ->selectSum('total', 'omset')
                    ->selectCount('id', 'jml_order')
                    ->where('user_id', $u['id'])
                    ->whereIn('status', ['confirmed', 'packing', 'ready_to_ship', 'shipped', 'delivered', 'completed'])
                    ->whereIn('id', $repeatOrderIds);

                if (!empty($startDate)) {
                    $orderQuery->where('tanggal_so >=', $startDate);
                }
                if (!empty($endDate)) {
                    $orderQuery->where('tanggal_so <=', $endDate);
                }
                $soRes = $orderQuery->get()->getRowArray();
                $omsetRepeat      = (float) ($soRes['omset'] ?? 0);
                $repeatOrderCount = (int) ($soRes['jml_order'] ?? 0);
            }

            // 4. Conversion Rate per CS
            // Hanya naik ketika ada repeat order. Jika tidak ada repeat order (repeatOrderCount == 0), tetap nol (0%).
            $totalInteractions = max($handlingCount, $sentCount);
            $conversionRate = 0;
            if ($repeatOrderCount > 0) {
                if ($totalInteractions > 0) {
                    $conversionRate = round(min(100, ($repeatOrderCount / $totalInteractions) * 100), 1);
                } else {
                    $conversionRate = 100.0;
                }
            } else {
                $conversionRate = 0.0;
            }

            $totalHandledAll += $handlingCount;
            $totalSentAll    += $sentCount;
            $totalOmsetAll   += $omsetRepeat;
            $totalConvSum    += $conversionRate;

            $csList[] = [
                'id'             => $u['id'],
                'username'       => $u['username'],
                'role'           => $u['role_name'] ?? 'Staff CS',
                'handling_count' => $handlingCount,
                'sent_count'     => $sentCount,
                'omset_repeat'   => $omsetRepeat,
                'conversion'     => $conversionRate,
                'is_current'     => ($u['id'] == $currentUserId),
            ];
        }

        // Urutkan Leaderboard: omset_repeat DESC, conversion DESC, sent_count DESC, handling_count DESC
        usort($csList, function ($a, $b) {
            if ($b['omset_repeat'] != $a['omset_repeat']) {
                return $b['omset_repeat'] <=> $a['omset_repeat'];
            }
            if ($b['conversion'] != $a['conversion']) {
                return $b['conversion'] <=> $a['conversion'];
            }
            if ($b['sent_count'] != $a['sent_count']) {
                return $b['sent_count'] <=> $a['sent_count'];
            }
            return $b['handling_count'] <=> $a['handling_count'];
        });

        $avgConvRate = count($csList) > 0 ? round($totalConvSum / count($csList), 1) : 0;

        $data = [
            'title'                => 'Performa Tim CS',
            'csAktif'              => count($csList),
            'totalFollowupHandled' => $totalHandledAll,
            'totalSentAll'         => $totalSentAll,
            'totalOmsetRepeat'     => $totalOmsetAll,
            'avgConversionRate'    => $avgConvRate,
            'csList'               => $csList,
            'startDate'            => $startDate,
            'endDate'              => $endDate,
            'selectedUserId'       => $selectedUserId,
            'allCsUsers'           => $allCsUsers,
            'currentUserId'        => $currentUserId,
        ];

        return view('Modules\CRM\Views\kinerja', $data);
    }

    private function ensureFollowupCategoriesTable(): void
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('crm_followup_categories')) {
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'code' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'days' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'H+0',
                ],
                'template' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['Aktif', 'Nonaktif'],
                    'default'    => 'Aktif',
                ],
                'order_index' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $forge->addKey('id', true);
            $forge->addKey('code');
            $forge->createTable('crm_followup_categories', true);
        }

        $count = $db->table('crm_followup_categories')->countAllResults();
        if ($count === 0) {
            $defaults = [
                [
                    'name'        => 'Konfirmasi Pengiriman',
                    'code'        => 'KONFIRMASI_KIRIM',
                    'days'        => 'H+0',
                    'template'    => "Halo Kak {{nama_customer}} 🙏\n\nPesanan Kakak dengan nomor {{no_pesanan}} sedang dalam proses pengiriman ya.\n\nNo. Resi: {{nomor_resi}}\nEkspedisi: {{kurir}}",
                    'status'      => 'Aktif',
                    'order_index' => 0,
                    'created_at'  => date('Y-m-d H:i:s'),
                ],
                [
                    'name'        => 'Cek Barang Sampai',
                    'code'        => 'H_3_CEK_SAMPAI',
                    'days'        => 'H+3',
                    'template'    => "Halo Kak {{nama_customer}} 😊\n\nApakah pesanan Kakak ({{no_pesanan}}) sudah diterima dengan baik?\n\nProduk: {{produk}}\nDikirim tanggal: {{tanggal_kirim}}",
                    'status'      => 'Aktif',
                    'order_index' => 1,
                    'created_at'  => date('Y-m-d H:i:s'),
                ],
                [
                    'name'        => 'Tanya Pengalaman',
                    'code'        => 'H_7_TANYA_PENGALAMAN',
                    'days'        => 'H+7',
                    'template'    => "Halo Kak {{nama_customer}} ✨\n\nBagaimana pengalaman Kakak memakai produk {{produk}} kami? Jika ada kendala teknis atau saran, silakan balas pesan ini ya kak!",
                    'status'      => 'Aktif',
                    'order_index' => 2,
                    'created_at'  => date('Y-m-d H:i:s'),
                ],
                [
                    'name'        => 'Evaluasi Produk',
                    'code'        => 'H_11_EVALUASI',
                    'days'        => 'H+11',
                    'template'    => "Halo Kak {{nama_customer}}! Kami sedang mengevaluasi kualitas pesanan {{no_pesanan}}. Ada voucher khusus untuk Kakak belanja berikutnya!",
                    'status'      => 'Aktif',
                    'order_index' => 3,
                    'created_at'  => date('Y-m-d H:i:s'),
                ],
                [
                    'name'        => 'Tawarkan Repeat Order',
                    'code'        => 'H_30_REPEAT_ORDER',
                    'days'        => 'H+30',
                    'template'    => "Halo Kak {{nama_customer}} 🎁\n\nStok produk {{produk}} Kakak mungkin sudah mulai menipis? Yuk restock sekarang dan dapatkan diskon member 10%!",
                    'status'      => 'Aktif',
                    'order_index' => 4,
                    'created_at'  => date('Y-m-d H:i:s'),
                ],
                [
                    'name'        => 'Reaktivasi Pelanggan Lama',
                    'code'        => 'H_100_REAKTIVASI',
                    'days'        => 'H+100',
                    'template'    => "Halo Kak {{nama_customer}}, kami rindu kehadiran Kakak di Metafora! Cek koleksi produk terbaru kami hari ini.",
                    'status'      => 'Aktif',
                    'order_index' => 5,
                    'created_at'  => date('Y-m-d H:i:s'),
                ],
            ];
            $db->table('crm_followup_categories')->insertBatch($defaults);
        }
    }

    public function kelompok()
    {
        $this->ensureFollowupCategoriesTable();
        $db = \Config\Database::connect();
        $categories = $db->table('crm_followup_categories')
            ->orderBy('order_index', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title'      => 'Kelompok & Template Follow-Up',
            'categories' => $categories,
        ];

        return view('Modules\CRM\Views\kelompok', $data);
    }

    public function storeKelompok()
    {
        $this->ensureFollowupCategoriesTable();
        $db = \Config\Database::connect();

        $id         = (int) ($this->request->getPost('id') ?? 0);
        $name       = trim((string) ($this->request->getPost('nama') ?? ''));
        $code       = trim((string) ($this->request->getPost('code') ?? ''));
        $daysInput  = trim((string) ($this->request->getPost('days') ?? '0'));
        $template   = trim((string) ($this->request->getPost('template') ?? ''));
        $orderIndex = (int) ($this->request->getPost('order_index') ?? 0);
        $status     = trim((string) ($this->request->getPost('status') ?? 'Aktif'));

        if ($name === '') {
            return redirect()->back()->withInput()->with('error', 'Nama kategori follow-up wajib diisi.');
        }

        if ($code === '') {
            $code = strtoupper(preg_replace('/[^A-Za-z0-9_]/', '_', $name));
        }

        $cleanDays = preg_replace('/[^0-9]/', '', $daysInput);
        $days = ($cleanDays !== '') ? 'H+' . $cleanDays : 'H+0';

        $payload = [
            'name'        => $name,
            'code'        => strtoupper(str_replace(' ', '_', $code)),
            'days'        => $days,
            'template'    => $template,
            'status'      => ($status === 'Nonaktif') ? 'Nonaktif' : 'Aktif',
            'order_index' => $orderIndex,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($id > 0) {
            $db->table('crm_followup_categories')->where('id', $id)->update($payload);
            return redirect()->to('crm/kelompok')->with('success', "Kategori & template '{$name}' berhasil diperbarui.");
        }

        $payload['created_at'] = date('Y-m-d H:i:s');
        $db->table('crm_followup_categories')->insert($payload);

        return redirect()->to('crm/kelompok')->with('success', "Kategori baru '{$name}' ({$days}) berhasil ditambahkan ke daftar kelompok follow-up.");
    }

    public function deleteKelompok(int $id)
    {
        $this->ensureFollowupCategoriesTable();
        $db = \Config\Database::connect();
        $cat = $db->table('crm_followup_categories')->where('id', $id)->get()->getRowArray();
        if ($cat) {
            $db->table('crm_followup_categories')->where('id', $id)->delete();
            return redirect()->to('crm/kelompok')->with('success', "Kategori '{$cat['name']}' berhasil dihapus.");
        }
        return redirect()->to('crm/kelompok')->with('error', 'Kategori follow-up tidak ditemukan.');
    }

    public function detailModal(int $id)
    {
        $db = \Config\Database::connect();
        $sch = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.nomor_so, so.tanggal_so, so.total, so.source, pm.nomor_resi, pm.kurir, p.id as pelanggan_id')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left')
            ->join('pelanggan p', 'p.id = so.pelanggan_id', 'left')
            ->where('cfl.id', $id)
            ->get()->getRowArray();

        if (!$sch) {
            return $this->response->setJSON(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        $items = $db->table('sales_order_item soi')
            ->select('soi.*, p.nama_produk, p.kode_produk')
            ->join('produk p', 'p.id = soi.produk_id', 'left')
            ->where('soi.so_id', $sch['order_id'])
            ->get()->getResultArray();

        $notes = $db->table('crm_followup_notes cfn')
            ->select('cfn.*, u.username as user_name')
            ->join('users u', 'u.id = cfn.user_id', 'left')
            ->where('cfn.pelanggan_id', $sch['pelanggan_id'])
            ->orderBy('cfn.id', 'DESC')
            ->get()->getResultArray();

        $allLabels = $db->table('crm_transaction_followup_labels')
            ->where('order_id', $sch['order_id'])
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => true,
            'data'   => [
                'schedule'  => $sch,
                'items'     => $items,
                'notes'     => $notes,
                'allLabels' => $allLabels,
            ]
        ]);
    }

    public function export()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        $todayTimestamp = strtotime($today);
        $status = $this->request->getGet('status') ?: 'all';

        $builder = $db->table('crm_transaction_followup_labels cfl')
            ->select('cfl.*, so.nomor_so, so.tanggal_so, so.source, pm.nomor_resi, pm.no_pesanan')
            ->join('sales_order so', 'so.id = cfl.order_id', 'left')
            ->join('penjualan_marketplace pm', 'pm.so_id = so.id', 'left');

        if ($status !== 'all') {
            $builder->where('cfl.status', $status);
        }

        $schedules = $builder->orderBy('cfl.target_date', 'ASC')->get()->getResultArray();

        $labelNames = [
            'H+0'   => 'Konfirmasi Pengiriman (H+0)',
            'H+3'   => 'Cek Barang Sampai (H+3)',
            'H+7'   => 'Tanya Pengalaman (H+7)',
            'H+11'  => 'Evaluasi Produk (H+11)',
            'H+30'  => 'Tawarkan Repeat Order (H+30)',
            'H+100' => 'Testing Kelompok (H+100)',
        ];

        $headers = [
            'No',
            'Customer',
            'No. HP',
            'No. Pesanan',
            'Sumber',
            'Kategori Follow-Up',
            'Tgl Kirim',
            'Jadwal Follow-Up',
            'Keterangan',
            'Keterangan BC',
            'Status',
            'Broadcast Terkirim',
        ];

        $rows = [];
        foreach ($schedules as $idx => $s) {
            $isSent = ($s['status'] === 'sent');
            $rawTglKirim = $s['tanggal_so'] ?? $s['created_at'];
            $tglKirimFmt = !empty($rawTglKirim) ? date('d/m/Y', strtotime($rawTglKirim)) : '-';
            $jadwalFuFmt = !empty($s['target_date']) ? date('d/m/Y', strtotime($s['target_date'])) : '-';

            // Umur hari sejak kirim (H+X)
            $umurHari = 0;
            if (!empty($rawTglKirim)) {
                $umurHari = max(0, (int) floor(($todayTimestamp - strtotime(date('Y-m-d', strtotime($rawTglKirim)))) / 86400));
            }

            // Kategori
            $kategoriFull = $labelNames[$s['label_code']] ?? ($s['label_code'] ? "Follow-Up ({$s['label_code']})" : 'Follow-Up');

            if ($isSent) {
                $keterangan = "Sudah Di-Follow up (✓ {$s['label_code']})";
                $keteranganBC = $kategoriFull;
                $statusText = 'Sudah';
                $bcTerkirim = 'Sudah';
            } else {
                $keteranganBC = 'Belum Ada Follow-Up';
                $bcTerkirim = 'Belum';

                if ($s['target_date'] === $today) {
                    $keterangan = "Jadwal Hari Ini ({$s['label_code']})";
                    $statusText = 'Pending';
                } elseif ($s['target_date'] < $today) {
                    $keterangan = "Terlewat (H+{$umurHari})";
                    $statusText = 'Overdue';
                } else {
                    $keterangan = "Mendatang (H+{$umurHari})";
                    $statusText = 'Pending';
                }
            }

            // Sumber penjualan
            $source = ucfirst($s['source'] ?: 'Toko');
            if (strtolower($source) === 'tiktok') {
                $source = 'TikTok Shop';
            }

            $rawPhone = preg_replace('/[^0-9+]/', '', (string)$s['customer_phone']);
            $phone = !empty($rawPhone) ? $rawPhone : '-';
            $noPesanan = !empty($s['nomor_so']) ? $s['nomor_so'] : (!empty($s['no_pesanan']) ? $s['no_pesanan'] : (string) $s['order_id']);

            $rows[] = [
                $idx + 1,
                $s['customer_nama'] ?: 'Pelanggan',
                $phone,
                $noPesanan,
                $source,
                $kategoriFull,
                $tglKirimFmt,
                $jadwalFuFmt,
                $keterangan,
                $keteranganBC,
                $statusText,
                $bcTerkirim,
            ];
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        $filename = 'Export_Journal_FollowUp_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM for Microsoft Excel Windows compatibility
        fputs($out, "\xEF\xBB\xBF");

        // Baris 1: Judul Laporan persis foto
        fputcsv($out, ['LAPORAN JOURNAL FOLLOW-UP CRM & AFTER SALES']);
        // Baris 2: Metadata Tanggal & Total Entri
        fputcsv($out, ['Tanggal Ekspor: ' . date('d/m/Y H:i:s') . ' | Total Data: ' . count($rows) . ' Entri']);
        // Baris 3: Baris kosong
        fputcsv($out, []);
        // Baris 4: Header Kolom
        fputcsv($out, $headers);

        foreach ($rows as $row) {
            fputcsv($out, $row);
        }

        fclose($out);
        exit;
    }

    public function exportDirectory()
    {
        $db = \Config\Database::connect();
        $customers = $db->table('pelanggan p')
            ->select('p.kode_pelanggan, p.nama_pelanggan, p.no_telp, p.email, p.kota, 
                      COUNT(so.id) as total_order, 
                      MAX(so.tanggal_so) as last_order_date, 
                      COALESCE(SUM(so.total), 0) as total_spend')
            ->join('sales_order so', 'so.pelanggan_id = p.id AND so.status != "cancelled"', 'left')
            ->where('p.deleted_at IS NULL')
            ->groupBy(['p.id', 'p.kode_pelanggan', 'p.nama_pelanggan', 'p.no_telp', 'p.email', 'p.kota'])
            ->orderBy('total_order', 'DESC')
            ->get()->getResultArray();

        $headers = [
            'No', 'Kode Pelanggan', 'Nama Pelanggan', 'No. WhatsApp', 'Email', 
            'Kota', 'Total Transaksi Order', 'Order Terakhir', 'Total Nilai Belanja (Rp)'
        ];

        $rows = [];
        foreach ($customers as $idx => $c) {
            $rows[] = [
                $idx + 1,
                $c['kode_pelanggan'],
                $c['nama_pelanggan'],
                $c['no_telp'] ?? '-',
                $c['email'] ?? '-',
                $c['kota'] ?? '-',
                $c['total_order'],
                $c['last_order_date'] ?? '-',
                $c['total_spend'],
            ];
        }

        export_csv_file('Export_CRM_Directory_Pelanggan_' . date('Ymd_His') . '.csv', $headers, $rows);
    }

    public function exportKinerja()
    {
        $this->ensureCrmColumnsExist();
        $db = \Config\Database::connect();
        $startDate      = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate        = $this->request->getGet('end_date') ?: date('Y-m-d');
        $selectedUserId = (int) ($this->request->getGet('user_id') ?: 0);

        $userBuilder = $db->table('users u')
            ->select('u.id, u.username, u.email, r.name as role_name')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->where('u.status', 'active');

        if ($selectedUserId > 0) {
            $userBuilder->where('u.id', $selectedUserId);
        }

        $csUsers = $userBuilder->orderBy('u.id', 'ASC')->get()->getResultArray();

        // Cari ID pesanan yang benar-benar berstatus Repeat Order (bukan pesanan perdana / pertama).
        $repeatOrderIds = [];
        if ($db->tableExists('sales_order')) {
            $repOrdersQuery = $db->query("
                SELECT so.id
                FROM sales_order so
                WHERE so.status IN ('confirmed', 'packing', 'ready_to_ship', 'shipped', 'delivered', 'completed')
                  AND so.pelanggan_id IS NOT NULL
                  AND EXISTS (
                      SELECT 1 FROM sales_order so_prev
                      WHERE so_prev.pelanggan_id = so.pelanggan_id
                        AND so_prev.status IN ('confirmed', 'packing', 'ready_to_ship', 'shipped', 'delivered', 'completed')
                        AND (so_prev.tanggal_so < so.tanggal_so OR (so_prev.tanggal_so = so.tanggal_so AND so_prev.id < so.id))
                  )
            ");
            if ($repOrdersQuery) {
                $repRows = $repOrdersQuery->getResultArray();
                $repeatOrderIds = array_column($repRows, 'id');
            }
        }

        $headers = ['No', 'Nama CS / User', 'Role Jabatan', 'Email', 'Handling Count', 'WA Sent', 'Omset Repeat Order (Rp)', 'Conversion Rate (%)', 'Status'];
        $rows = [];
        foreach ($csUsers as $idx => $u) {
            $handlingQuery = $db->table('crm_followup_notes')->where('user_id', $u['id']);
            if (!empty($startDate)) {
                $handlingQuery->where('created_at >=', $startDate . ' 00:00:00');
            }
            if (!empty($endDate)) {
                $handlingQuery->where('created_at <=', $endDate . ' 23:59:59');
            }
            $handlingCount = $handlingQuery->countAllResults();

            $sentQuery = $db->table('crm_transaction_followup_labels')
                ->where('status', 'sent')
                ->where('user_id', $u['id']);
            if (!empty($startDate)) {
                $sentQuery->where('sent_at >=', $startDate . ' 00:00:00');
            }
            if (!empty($endDate)) {
                $sentQuery->where('sent_at <=', $endDate . ' 23:59:59');
            }
            $sentCount = $sentQuery->countAllResults();

            // 3. Omset Repeat Order per CS (Hanya pesanan yang benar-benar Repeat Order)
            $omsetRepeat      = 0;
            $repeatOrderCount = 0;
            if (!empty($repeatOrderIds)) {
                $orderQuery = $db->table('sales_order')
                    ->selectSum('total', 'omset')
                    ->selectCount('id', 'jml_order')
                    ->where('user_id', $u['id'])
                    ->whereIn('status', ['confirmed', 'packing', 'ready_to_ship', 'shipped', 'delivered', 'completed'])
                    ->whereIn('id', $repeatOrderIds);

                if (!empty($startDate)) {
                    $orderQuery->where('tanggal_so >=', $startDate);
                }
                if (!empty($endDate)) {
                    $orderQuery->where('tanggal_so <=', $endDate);
                }
                $soRes = $orderQuery->get()->getRowArray();
                $omsetRepeat      = (float) ($soRes['omset'] ?? 0);
                $repeatOrderCount = (int) ($soRes['jml_order'] ?? 0);
            }

            // 4. Conversion Rate per CS
            // Hanya naik ketika ada repeat order. Jika tidak ada repeat order (repeatOrderCount == 0), tetap nol (0%).
            $totalInteractions = max($handlingCount, $sentCount);
            $conversionRate = 0;
            if ($repeatOrderCount > 0) {
                if ($totalInteractions > 0) {
                    $conversionRate = round(min(100, ($repeatOrderCount / $totalInteractions) * 100), 1);
                } else {
                    $conversionRate = 100.0;
                }
            } else {
                $conversionRate = 0.0;
            }

            $rows[] = [
                $idx + 1,
                $u['username'],
                strtoupper($u['role_name'] ?? 'CS'),
                $u['email'] ?? '-',
                $handlingCount,
                $sentCount,
                $omsetRepeat,
                $conversionRate . '%',
                'Aktif',
            ];
        }

        export_csv_file('Export_CRM_Kinerja_CS_' . date('Ymd_His') . '.csv', $headers, $rows);
    }
}
