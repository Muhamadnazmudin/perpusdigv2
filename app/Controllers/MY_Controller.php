<?php

require_once __DIR__ . '/CI_Controller.php';

#[\AllowDynamicProperties]
class MY_Controller extends CI_Controller
{
    public array $user = [];
    public array $data = [];

    public function __construct()
    {
        parent::__construct();

        if (is_cli()) {
            $this->user = $this->session->userdata('user') ?: [
                'id_user'  => 1,
                'id_role'  => 1,
                'username' => 'admin',
                'nama'     => 'Administrator'
            ];
            $this->data['total_menunggu'] = 0;
            return;
        }

        if (strpos($this->router->class, '.') !== false) {
            return;
        }

        $public_controllers = ['home', 'auth'];
        $current_controller = strtolower($this->router->class);

        if (!in_array($current_controller, $public_controllers, true)) {
            if (!$this->session->userdata('login')) {
                redirect('auth/login');
                exit;
            }

            $this->user = $this->session->userdata('user');

            if (!isset($this->user['id_role'])) {
                $this->session->sess_destroy();
                redirect('auth/login');
                exit;
            }

            $this->user['id_role'] = (int) $this->user['id_role'];
        }

        // 🔔 NOTIF PENGAJUAN PEMINJAMAN (ADMIN)
        if (!empty($this->user) && isset($this->user['id_role']) && $this->user['id_role'] === 1) {
            $this->load->model('Peminjaman_model');
            $this->data['total_menunggu'] = $this->Peminjaman_model->count_menunggu();
        } else {
            $this->data['total_menunggu'] = 0;
        }
    }

    protected function only_role(array $roles)
    {
        if (is_cli()) {
            return;
        }

        if (!isset($this->user['id_role']) || !in_array((int)$this->user['id_role'], $roles, true)) {
            show_error('Akses ditolak', 403);
        }
    }
}

if (!class_exists('App\Controllers\MY_Controller', false)) {
    class_alias('MY_Controller', 'App\Controllers\MY_Controller');
}
