<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Backup extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]); // ADMIN ONLY

        $this->load->model('Backup_model');
        $this->load->helper(['form','download']);
    }

    public function index()
    {
        $data['title'] = 'Backup & Restore Database';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('backup/index', $data);
        $this->load->view('templates/footer');
    }

    // ================= BACKUP =================
    public function backup()
    {
        $file = $this->Backup_model->backup_database();
        force_download($file['name'], $file['data']);
    }

    // ================= RESTORE =================
    public function restore()
    {
        if (empty($_FILES['file']['name'])) {
            $this->session->set_flashdata('error','File backup belum dipilih');
            redirect('backup');
        }

        $this->Backup_model->restore_database($_FILES['file']['tmp_name']);

        $this->session->set_flashdata('success','Database berhasil direstore');
        redirect('backup');
    }
}
