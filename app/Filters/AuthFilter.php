<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('login') || !session()->get('user')) {
            return redirect()->to(site_url('auth/login'));
        }

        if (!empty($arguments)) {
            $user = session()->get('user');
            $roleId = (int)($user['id_role'] ?? 0);
            $allowedRoles = array_map('intval', $arguments);
            if (!in_array($roleId, $allowedRoles, true)) {
                $response = service('response');
                $response->setStatusCode(403);
                $response->setBody('Akses ditolak (403)');
                return $response;
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}

