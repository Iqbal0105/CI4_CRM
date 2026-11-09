<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user = $session->get('user');

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $user['role_name'] ?? '';

        // Superadmin always has full access
        if ($userRole === 'superadmin') {
            return null;
        }

        if (!empty($arguments)) {
            if (!in_array($userRole, $arguments, true)) {
                if ($request->isAJAX()) {
                    return service('response')
                        ->setStatusCode(403)
                        ->setJSON([
                            'status'  => 'error',
                            'message' => 'Akses ditolak: Peran Anda (' . $userRole . ') tidak memiliki izin untuk halaman ini.',
                        ]);
                }

                return redirect()->to('/dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}

