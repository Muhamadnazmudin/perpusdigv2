<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class SiswaEbook extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([2,3]); // SISWA
        $this->load->model('Ebook_model');
        $this->load->model('Riwayat_model');
        $this->load->library('pagination');
    }

    /* ===================== INDEX ===================== */
    public function index()
    {
        $kelas = $this->input->get('kelas', TRUE);
        $mapel = $this->input->get('mapel', TRUE);

        $limit  = 9;
        $offset = $this->input->get('per_page') ?? 0;

        // 🔐 model sudah difilter APPROVED + PUBLIC
        $total = $this->Ebook_model
                      ->count_filtered(null, $kelas, $mapel);

        $config['base_url'] = site_url('SiswaEbook') . '?' . http_build_query([
    'kelas' => $kelas,
    'mapel' => $mapel
]);
$config['total_rows'] = $total;
$config['per_page']  = $limit;
$config['page_query_string'] = TRUE;

// 🔥 TAMBAHAN UNTUK TENGAH
$config['full_tag_open']  = '<nav class="d-flex justify-content-center"><ul class="pagination">';
$config['full_tag_close'] = '</ul></nav>';
$config['attributes']     = ['class' => 'page-link'];
$config['num_tag_open']   = '<li class="page-item">';
$config['num_tag_close']  = '</li>';
$config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
$config['cur_tag_close']  = '</span></li>';

$this->pagination->initialize($config);


        $this->pagination->initialize($config);

        $data = [
            'title'         => 'E-Book Digital',
            'ebook'         => $this->Ebook_model
                                    ->get_filtered($limit, $offset, null, $kelas, $mapel),
            'pagination'    => $this->pagination->create_links(),
            'mapel_list'    => $this->Ebook_model->get_mapel_unik(),
            'filter_kelas'  => $kelas,
            'filter_mapel'  => $mapel
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/ebook/index', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== BACA ===================== */
    public function baca($id)
    {
        $this->only_role([2,3]);

        $ebook = $this->Ebook_model->get_by_id($id);

        // 🔐 BLOK AKSES JIKA BELUM APPROVED
        if (
            !$ebook ||
            $ebook->status !== 'APPROVED' ||
            (int)$ebook->is_public !== 1
        ) {
            show_404();
        }

        // simpan riwayat baca
        $this->Riwayat_model->save_or_update(
            $this->user['id_user'],
            $id
        );

        $data['title'] = $ebook->judul;
        $data['ebook'] = $ebook;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/ebook/baca', $data);
        $this->load->view('templates/footer');
    }

    /* ===================== RIWAYAT ===================== */
    public function riwayat()
    {
        $data = [
            'title' => 'Riwayat Bacaan Saya',
            'ebook' => $this->Riwayat_model
                            ->get_by_user($this->user['id_user'])
        ];

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/ebook/riwayat',$data);
        $this->load->view('templates/footer');
    }

    /* ===================== FAVORIT ===================== */
    public function favorit($id)
    {
        $this->load->model('Favorit_model');
        $this->Favorit_model->toggle($this->user['id_user'], $id);
        redirect('SiswaEbook/baca/'.$id);
    }

    /* ===================== UPDATE PROGRESS ===================== */
    public function update_progress()
    {
        $page     = $this->input->post('page');
        $id_ebook = $this->input->post('id_ebook');

        $this->Riwayat_model->update_progress(
            $this->user['id_user'],
            $id_ebook,
            $page
        );
    }
}
