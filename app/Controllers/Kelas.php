<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Kelas extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Kelas_model');
    }

    public function index()
    {
        $data['title'] = 'Data Kelas';
        $data['kelas'] = $this->Kelas_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $this->data);
$this->load->view('templates/topbar', $this->data);
        $this->load->view('kelas/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        $this->Kelas_model->insert([
            'nama_kelas' => $this->input->post('nama_kelas')
        ]);
        redirect('kelas');
    }

    public function hapus($id)
    {
        $this->Kelas_model->delete($id);
        redirect('kelas');
    }
}
