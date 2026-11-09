<?php

declare(strict_types=1);

namespace Modules\CRM\Services;

class WhatsAppGatewayService
{
    protected string $fonnteToken;

    public function __construct()
    {
        $this->fonnteToken = (string) (getenv('FONNTE_TOKEN') ?: '');
    }

    /**
     * Normalisasi nomor telepon ke format internasional 628...
     */
    public function normalizePhoneNumber(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }
        return $clean;
    }

    /**
     * Compile template message variables.
     */
    public function compileTemplate(string $template, array $vars): string
    {
        $nama = !empty($vars['nama']) ? trim((string) $vars['nama']) : 'Pelanggan Setia';
        $resi = !empty($vars['nomor_resi']) ? trim((string) $vars['nomor_resi']) : '-';
        $produk = !empty($vars['produk']) ? trim((string) $vars['produk']) : 'pesanan Anda';

        $search = ['{nama}', '{nomor_resi}', '{produk}'];
        $replace = [$nama, $resi, $produk];

        return str_replace($search, $replace, $template);
    }

    /**
     * Send message to WhatsApp recipient.
     */
    public function sendMessage(string $phone, string $message): array
    {
        $normalizedPhone = $this->normalizePhoneNumber($phone);
        if (strlen($normalizedPhone) < 10) {
            return [
                'status'  => false,
                'message' => 'Nomor WhatsApp tidak valid: ' . $phone,
            ];
        }

        // If no Fonnte Token is provided in .env, simulate successful delivery
        if (empty($this->fonnteToken)) {
            return [
                'status'  => true,
                'message' => 'Simulasi Berhasil (Sandbox Mode / Fonnte Token belum diisi di .env)',
                'detail'  => ['phone' => $normalizedPhone, 'message' => $message],
            ];
        }

        // Live Fonnte HTTP API Call
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.fonnte.com/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'target'  => $normalizedPhone,
            'message' => $message,
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $this->fonnteToken,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'status'  => false,
                'message' => 'cURL Error: ' . $error,
            ];
        }

        $resJson = json_decode((string) $response, true);
        $isSuccess = ($httpCode >= 200 && $httpCode < 300) && ($resJson['status'] ?? true);

        return [
            'status'  => (bool) $isSuccess,
            'message' => $resJson['reason'] ?? ($isSuccess ? 'Pesan terkirim' : 'Gagal mengirim pesan'),
            'raw'     => $resJson,
        ];
    }
}

