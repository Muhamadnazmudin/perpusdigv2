<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Buku extends MY_Controller {

    public function __construct()
{
    parent::__construct();
    $this->only_role([2,3]); // SISWA

    $this->load->model([
        'Buku_fisik_model',
        'Peminjaman_model'
    ]);
}


    // LIST BUKU UNTUK SISWA
public function index()
{
    $this->load->library('pagination');

    $keyword = $this->input->get('q', true);
    $kelas   = $this->input->get('kelas', true);

    $limit  = 8;
    $page   = (int) $this->input->get('page');
    $offset = ($page > 1) ? ($page - 1) * $limit : 0;

    $total_rows = $this->Buku_fisik_model
        ->count_filtered_siswa($kelas, $keyword);

    $config = [
        'base_url'            => site_url('buku'),
        'total_rows'          => $total_rows,
        'per_page'            => $limit,
        'use_page_numbers'    => true,
        'page_query_string'   => true,
        'query_string_segment'=> 'page',
        'reuse_query_string'  => true,

        // Bootstrap center (PALING AMAN)
        'full_tag_open'  => '<nav class="d-flex justify-content-center"><ul class="pagination">',
        'full_tag_close' => '</ul></nav>',
        'attributes'     => ['class' => 'page-link'],
        'num_tag_open'   => '<li class="page-item">',
        'num_tag_close'  => '</li>',
        'cur_tag_open'   => '<li class="page-item active"><span class="page-link">',
        'cur_tag_close'  => '</span></li>',
        'prev_tag_open'  => '<li class="page-item">',
        'prev_tag_close' => '</li>',
        'next_tag_open'  => '<li class="page-item">',
        'next_tag_close' => '</li>',
    ];

    $this->pagination->initialize($config);

    $data['title'] = 'Buku Perpustakaan';
    $data['kelas'] = $this->Buku_fisik_model->get_list_kelas();
    $data['buku']  = $this->Buku_fisik_model
        ->get_filtered_siswa($kelas, $keyword, $limit, $offset);

    $data['pagination'] = $this->pagination->create_links();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('buku_siswa/index', $data);
    $this->load->view('templates/footer');
}

   // DETAIL BUKU UNTUK SISWA
public function detail($id)
{
    $this->only_role([2,3]); // SISWa dan Guru

    $data['buku'] = $this->Buku_fisik_model->get_detail($id);
    if (!$data['buku']) {
        show_404();
    }

    $data['title'] = 'Detail Buku';

    $data['total_pinjam'] = $this->Peminjaman_model
        ->count_pinjam_aktif($this->user['id_user']);

    // 🔥 STATUS PENGAJUAN YANG BENAR
    $pengajuan = $this->Peminjaman_model
        ->get_pengajuan_buku($this->user['id_user'], $id);

    $data['status_pengajuan'] = $pengajuan ? $pengajuan->status : null;

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar', $this->data);
    $this->load->view('buku_siswa/detail', $data);
    $this->load->view('templates/footer');
}

}
