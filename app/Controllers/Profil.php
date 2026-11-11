<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Profil extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([2,3]); // SISWA
        $this->load->model('Siswa_model');
    }

    public function index()
{
    $user = $this->user;

    if (!in_array($user['id_role'], [2,3])) {
        show_error('Akses ditolak');
    }

    // ================= SISWA =================
    if ($user['id_role'] == 3) {

        $nis = $user['username'];

        $siswa = $this->db
            ->select('
                siswa.nis,
                siswa.nama_siswa,
                siswa.foto,
                siswa.qr_code,
                kelas.nama_kelas,
                jurusan.nama_jurusan
            ')
            ->from('siswa')
            ->join('kelas', 'kelas.id_kelas = siswa.id_kelas')
            ->join('jurusan', 'jurusan.id_jurusan = siswa.id_jurusan')
            ->where('siswa.nis', $nis)
            ->get()
            ->row();

        if (!$siswa) {
            show_error('Data siswa tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Siswa',
            'siswa' => $siswa
        ];

        $view = 'siswa/profil';
    }

    // ================= GURU =================
    else {

        $nip = $user['username'];

        $guru = $this->db
            ->where('nip', $nip)
            ->get('guru')
            ->row();

        if (!$guru) {
            show_error('Data guru tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Guru',
            'guru'  => $guru
        ];

        $view = 'guru/profil';
    }

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view($view, $data);
    $this->load->view('templates/footer');
}
public function upload_foto()
{
    // pastikan login siswa
    $user = $this->session->userdata('user');
    if (!$user || !in_array($user['id_role'], [2,3])) {
        show_error('Akses ditolak');
    }

    $nis = $user['username'];

    // ambil data siswa lama (buat hapus foto lama)
    $siswa = $this->db
        ->select('foto')
        ->from('siswa')
        ->where('nis', $nis)
        ->get()
        ->row();

    if (!$siswa) {
        show_error('Data siswa tidak ditemukan');
    }

    // konfigurasi upload
    $config['upload_path']   = './uploads/siswa/';
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size']      = 2048;
    $config['file_name']     = 'siswa_' . $nis; // NAMA SAMA → REPLACE
    $config['overwrite']    = TRUE;

    $this->load->library('upload', $config);

    // validasi request
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
        redirect('profil');
    }

    // gagal upload
    if (!$this->upload->do_upload('foto')) {

        $this->session->set_flashdata(
            'error',
            strip_tags($this->upload->display_errors())
        );
        redirect('profil');
    }

    // sukses upload
    $data = $this->upload->data();
    $foto_baru = 'uploads/siswa/' . $data['file_name'];

    /**
     * HAPUS FOTO LAMA
     * - hanya jika ada
     * - bukan default
     * - file benar-benar ada
     */
    if (
        !empty($siswa->foto) &&
        $siswa->foto !== 'assets/img/user.png' &&
        file_exists(FCPATH . $siswa->foto)
    ) {
        unlink(FCPATH . $siswa->foto);
    }

    // update database
    $this->db->where('nis', $nis)
             ->update('siswa', ['foto' => $foto_baru]);

    $this->session->set_flashdata(
        'success',
        'Foto berhasil diperbarui'
    );

    redirect('profil');
}

}
