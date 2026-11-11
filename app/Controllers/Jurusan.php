<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Jurusan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Jurusan_model');
    }

    public function index()
    {
        $data['title']   = 'Data Jurusan';
        $data['jurusan'] = $this->Jurusan_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('jurusan/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
    {
        $this->Jurusan_model->insert([
            'nama_jurusan' => $this->input->post('nama_jurusan')
        ]);
        redirect('jurusan');
    }

    public function hapus($id)
    {
        $this->Jurusan_model->delete($id);
        redirect('jurusan');
    }
}
