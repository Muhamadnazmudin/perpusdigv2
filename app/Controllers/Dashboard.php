<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
    }

    public function index()
{
    $role    = $this->user['id_role'];
    $user_id = $this->user['id_user'];

    $data['title'] = 'Dashboard';

    // ================= ADMIN =================
    if ($role == 1) {

        $this->load->model('Settings_model');
        $data['maintenance_mode'] =
            $this->Settings_model->get('maintenance_mode');

        $data['stat'] =
            $this->Dashboard_model->get_stat_admin();

        $view = 'dashboard/admin';
    }

    elseif ($role == 2 || $role == 3) {

    $data['stat'] =
        $this->Dashboard_model->get_stat_user($user_id);

    $view = ($role == 2)
        ? 'dashboard/guru'
        : 'dashboard/siswa';
}

    else {
        show_error('Role tidak dikenal', 403);
    }

    // Template
    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar', $this->data);
    $this->load->view($view, $data);
    $this->load->view('templates/footer');
}
public function toggle_maintenance()
{
    if ($this->user['id_role'] != 1) {
        show_error('Forbidden', 403);
    }

    $this->load->model('Settings_model');

    $current = $this->Settings_model->get('maintenance_mode');
    $new = ($current == '1') ? '0' : '1';

    $this->Settings_model->set('maintenance_mode', $new);

    redirect('dashboard');
}

}
