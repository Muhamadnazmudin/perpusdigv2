<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Guru extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Guru_model');
    }

    public function index()
    {
        $data['title'] = 'Data Guru';
        $data['guru']  = $this->Guru_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('guru/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambah()
{
    $nip = $this->input->post('nip', true);

    // cek sudah ada atau belum
    $cek = $this->db->get_where('guru', ['nip' => $nip])->row();
    if ($cek) {
        $this->session->set_flashdata('error','NIP sudah terdaftar');
        redirect('guru');
    }

    // simpan guru
    $this->Guru_model->insert([
        'nip'       => $nip,
        'nama_guru' => $this->input->post('nama_guru', true),
        'email'     => $this->input->post('email', true)
    ]);

    // otomatis buat akun login guru
    $this->db->insert('users', [
        'id_role'  => 2, // ROLE GURU
        'username' => $nip,
        'password' => password_hash($nip, PASSWORD_DEFAULT),
        'nama'     => $this->input->post('nama_guru', true),
        'status'   => 'aktif'
    ]);

    $this->session->set_flashdata('success','Guru & akun login berhasil dibuat');
    redirect('guru');
}

    public function sync_guru_user()
{
    $guru = $this->db->get('guru')->result();

    foreach ($guru as $g) {
        $cek = $this->db->get_where('users', [
            'username' => $g->nip
        ])->row();

        if (!$cek) {
            $this->db->insert('users', [
                'id_role'  => 2,
                'username' => $g->nip,
                'password' => password_hash($g->nip, PASSWORD_DEFAULT),
                'nama'     => $g->nama_guru,
                'status'   => 'aktif'
            ]);
        }
    }

    echo "Sinkron guru → users selesai";
}

}
