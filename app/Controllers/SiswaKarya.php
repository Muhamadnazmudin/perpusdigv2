<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class SiswaKarya extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([2,3]); // SISWA dan guru
        $this->load->model('Karya_model');
        $this->load->library('upload'); // WAJIB
    }

    /* ===================== INDEX ===================== */
    public function index()
    {
        $data = [
            'title' => 'Karya Saya',
            'karya' => $this->Karya_model
                            ->get_by_user($this->user['id_user'])
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/karya/index', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== TAMBAH ===================== */
    public function tambah()
    {
        $data['title'] = 'Tambah Karya';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/karya/tambah', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== SIMPAN ===================== */
    public function simpan()
{
    $judul = trim($this->input->post('judul', true));
    $jenis = trim($this->input->post('jenis', true));

    // validasi wajib
    if ($judul === '' || $jenis === '') {
        $this->session->set_flashdata('error','Data wajib belum lengkap');
        redirect('SiswaKarya/tambah');
        return;
    }

    // wajib pdf
    if (empty($_FILES['file_local']['name'])) {
        $this->session->set_flashdata('error','File PDF wajib diupload');
        redirect('SiswaKarya/tambah');
        return;
    }

    // upload PDF dulu
    $file_pdf = $this->_upload_karya();
    if ($file_pdf === false) {
        // pesan error sudah di-set di fungsi upload
        redirect('SiswaKarya/tambah');
        return;
    }

    // data utama
    $data = [
        'judul'      => $judul,
        'jenis'      => $jenis,
        'mapel'      => $this->input->post('mapel', true),
        'kelas'      => strtoupper($this->input->post('kelas', true)),
        'source'     => 'LOCAL',
        'file_local' => $file_pdf,
        'cover'      => null,
        'created_by' => $this->user['id_user'],
        'status'     => 'PENDING',
        'is_public'  => 0,
        'created_at' => date('Y-m-d H:i:s')
    ];

    /* ===== COVER (OPSIONAL, AMAN) ===== */
    if (!empty($_FILES['cover']['name'])) {

        $cover = $this->_upload_cover();

        if ($cover === false) {
            $this->session->set_flashdata(
                'error',
                'Cover gagal diupload. Pastikan JPG/PNG dan maksimal 2MB.'
            );
            redirect('SiswaKarya/tambah');
            return;
        }

        $data['cover'] = $cover;
    }

    // simpan ke DB
    $this->db->insert('ebook', $data);

    $this->session->set_flashdata(
        'success',
        'Karya berhasil dikirim dan menunggu persetujuan admin'
    );

    redirect('SiswaKarya');
}

    /* ===================== EDIT ===================== */
    public function edit($id)
    {
        $karya = $this->Karya_model->get_by_id($id);

        if (
            !$karya ||
            $karya->created_by != $this->user['id_user']
        ) {
            show_404();
        }

        if ($karya->status === 'APPROVED') {
            $this->session->set_flashdata(
                'error',
                'Karya yang sudah disetujui tidak dapat diedit'
            );
            redirect('SiswaKarya');
        }

        $data = [
            'title' => 'Edit Karya',
            'karya' => $karya
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/karya/edit', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== UPDATE ===================== */
    public function update($id)
    {
        $karya = $this->Karya_model->get_by_id($id);

        if (
            !$karya ||
            $karya->created_by != $this->user['id_user'] ||
            $karya->status === 'APPROVED'
        ) {
            show_404();
        }

        $data = [
            'judul'     => $this->input->post('judul', true),
            'jenis'     => $this->input->post('jenis', true),
            'mapel'     => $this->input->post('mapel', true),
            'status'    => 'PENDING',
            'is_public' => 0
        ];

        /* ===== GANTI COVER (OPSIONAL) ===== */
        if (!empty($_FILES['cover']['name'])) {
            $data['cover'] = $this->_upload_cover();
            if ($data['cover'] === false) return;
        }

        $this->Karya_model->update_status($id, $data);

        $this->session->set_flashdata(
            'success',
            'Karya berhasil diperbarui dan menunggu review ulang'
        );

        redirect('SiswaKarya');
    }

    /* ===================== UPLOAD PDF ===================== */
    private function _upload_karya()
    {
        $config = [
            'upload_path'   => './assets/uploads/ebook/',
            'allowed_types' => 'pdf',
            'max_size'      => 10240,
            'encrypt_name'  => true
        ];

        $this->upload->initialize($config, true);

        if (!$this->upload->do_upload('file_local')) {
            $this->session->set_flashdata(
                'error',
                strip_tags($this->upload->display_errors())
            );
            redirect('SiswaKarya/tambah');
            exit;
        }

        return $this->upload->data('file_name');
    }

    /* ===================== UPLOAD COVER ===================== */
    private function _upload_cover()
{
    $config = [
        'upload_path'   => './assets/uploads/cover_ebook/',
        'allowed_types' => 'jpg|jpeg|png',
        'max_size'      => 2048,
        'encrypt_name'  => true
    ];

    $this->upload->initialize($config, true);

    if (!$this->upload->do_upload('cover')) {
        // SIMPAN ERROR KE FLASH
        $error = strip_tags($this->upload->display_errors());
        $this->session->set_flashdata('error', $error);
        return false;
    }

    return $this->upload->data('file_name');
}


}
