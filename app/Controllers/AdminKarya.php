<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class AdminKarya extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]); // ADMIN ONLY
        $this->load->model('Karya_model');
    }

    /* ===================== INDEX ===================== */
    public function index()
{
    $data = [
        'title' => 'Approval Karya Siswa',
        'karya' => $this->Karya_model->get_all()
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('admin/karya/index', $data);
    $this->load->view('templates/footer');
}


    /* ===================== DETAIL ===================== */
    public function detail($id)
    {
        $karya = $this->Karya_model->get_by_id($id);
        if (!$karya) show_404();

        $data = [
            'title' => 'Review Karya',
            'karya' => $karya
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/karya/detail', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== APPROVE ===================== */
    public function approve($id)
    {
        $this->Karya_model->update_status($id, [
            'status'    => 'APPROVED',
            'is_public' => 1
        ]);

        $this->session->set_flashdata(
            'success',
            'Karya berhasil disetujui'
        );

        redirect('AdminKarya');
    }

    /* ===================== REJECT ===================== */
    public function reject($id)
    {
        $this->Karya_model->update_status($id, [
            'status'    => 'REJECTED',
            'is_public' => 0
        ]);

        $this->session->set_flashdata(
            'success',
            'Karya ditolak'
        );

        redirect('AdminKarya');
    }
}
