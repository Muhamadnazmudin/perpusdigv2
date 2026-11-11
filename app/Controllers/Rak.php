<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Rak extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Rak_model');
    }

    public function index()
    {
        $data['title'] = 'Master Rak';
        $data['rak'] = $this->Rak_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('rak/index', $data);
        $this->load->view('templates/footer');
    }

    public function simpan()
    {
        $this->Rak_model->insert([
            'kode_rak' => $this->input->post('kode_rak', TRUE),
            'lokasi'   => $this->input->post('lokasi', TRUE)
        ]);

        $this->session->set_flashdata('success', 'Rak berhasil ditambahkan');
        redirect('rak');
    }
}
