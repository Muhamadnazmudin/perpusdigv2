<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Quran extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        // 🔐 WAJIB LOGIN (semua role boleh akses)
        if (!$this->user) {
            redirect('auth/login');
        }

        $this->load->model('Quran_model');
    }

    // 📚 Daftar Surah
    public function index()
{
    $data['title']  = 'Baca Al-Qur\'an';
    $data['surah']  = $this->Quran_model->get_all_surah();

    // ambil id user dengan aman
    $user_id = $this->user['id_user']; // ← GANTI sesuai kolom di tabel users kamu

    $data['last']   = $this->Quran_model->get_last_bookmark($user_id);

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar',  $this->data);
    $this->load->view('quran/index', $data);
    $this->load->view('templates/footer');
}

    // 📖 Detail Surah
    public function baca($surah_id)
    {
        $surah = $this->Quran_model->get_surah($surah_id);

        if (!$surah) {
            show_404();
        }

        $data['title'] = 'Surah ' . $surah->nama_latin;
        $data['surah'] = $surah;
        $data['ayat']  = $this->Quran_model->get_ayat($surah_id);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $this->data);
        $this->load->view('templates/topbar',  $this->data);
        $this->load->view('quran/baca', $data);
        $this->load->view('templates/footer');
    }

    // 🔖 Bookmark Ayat
    public function bookmark($surah_id, $ayat_id)
{
    $user_id = $this->user['id_user']; // sesuaikan

    $this->Quran_model->set_bookmark(
        $user_id,
        $surah_id,
        $ayat_id
    );

    redirect('quran/baca/' . $surah_id);
}
}