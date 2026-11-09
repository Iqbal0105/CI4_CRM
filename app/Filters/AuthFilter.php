<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('is_logged_in') || !$session->get('user')) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                    ]);
            }

            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses sistem.');
        }

        // Security Hardening: Real-time Account Status & Revocation Check
        $userId = $session->get('user')['id'] ?? null;
        if ($userId) {
            try {
                $db = \Config\Database::connect();
                $userRecord = $db->table('users')->select('id, status')->where('id', $userId)->get()->getRowArray();
                if (!$userRecord || $userRecord['status'] !== 'active') {
                    $session->destroy();
                    if ($request->isAJAX()) {
                        return service('response')
                            ->setStatusCode(403)
                            ->setJSON([
                                'status'  => 'error',
                                'message' => 'Akun Anda sedang dinonaktifkan atau telah dihapus oleh Administrator.',
                            ]);
                    }

                    return redirect()->to('/login')->with('error', 'Akun Anda sedang dinonaktifkan atau telah dihapus oleh Administrator.');
                }
            } catch (\Throwable $e) {
                // If database connection temporary error, allow proceed if session exists
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}

