<?php

declare(strict_types=1);

namespace Modules\CRM\Controllers;

use App\Controllers\BaseController;
use Modules\CRM\Services\WhatsAppGatewayService;

class CrmWebhookController extends BaseController
{
    /**
     * Webhook receiver for Fonnte / Wablas incoming messages.
     * CSRF exempt.
     */
    public function fonnte()
    {
        $raw = $this->request->getBody();
        $json = json_decode($raw, true) ?: $this->request->getPost();

        if (empty($json)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Empty payload']);
        }

        $sender = $json['sender'] ?? ($json['phone'] ?? '');
        $message = $json['message'] ?? ($json['text'] ?? '');

        if (!empty($sender) && !empty($message)) {
            $waService = new WhatsAppGatewayService();
            $normSender = $waService->normalizePhoneNumber((string) $sender);

            $db = \Config\Database::connect();

            // Match sender with existing customer if possible
            $cust = $db->table('pelanggan')->where('no_telp', $normSender)->get()->getRowArray();
            $pelangganId = $cust ? (int) $cust['id'] : null;

            // Log inbound chat note
            $db->table('crm_followup_notes')->insert([
                'pelanggan_id'   => $pelangganId,
                'customer_phone' => $normSender,
                'user_id'        => null,
                'label'          => 'New',
                'catatan'        => $message,
                'direction'      => 'inbound',
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Webhook received successfully',
        ]);
    }
}

