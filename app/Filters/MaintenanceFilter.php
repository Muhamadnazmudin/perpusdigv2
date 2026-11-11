<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (is_cli()) {
            return;
        }

        try {
            $db = \Config\Database::connect();
            $row = $db->table('settings')->where('name', 'maintenance_mode')->get()->getRow();
            if (!$row || (string)$row->value !== '1') {
                return;
            }
        } catch (\Throwable $e) {
            return;
        }

        $uri = service('uri');
        $path = trim($uri->getPath(), '/');

        if (strpos($path, 'login/admin') !== false || strpos($path, 'auth/login_admin') !== false) {
            return;
        }

        $user = session()->get('user');
        if (!empty($user) && isset($user['id_role']) && (int)$user['id_role'] === 1) {
            return;
        }

        $response = service('response');
        $response->setStatusCode(503);
        $response->setBody(view('maintenance'));
        return $response;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}

