<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Laporan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]); // ADMIN
        $this->load->model([
            'Kunjungan_model',
            'Peminjaman_model',
            'Buku_fisik_model'
        ]);
    }

    /* ================= LAPORAN KUNJUNGAN ================= */
    public function kunjungan()
{
    $this->load->library('pagination');

    $filter = $this->input->get();

    $page  = $this->input->get('page');
    $page  = is_numeric($page) ? $page : 0;
    $limit = 10;

    $total = $this->Kunjungan_model->count_laporan($filter);

    // ===== CONFIG PAGINATION =====
    $config['base_url'] = site_url('laporan/kunjungan');
    $config['total_rows'] = $total;
    $config['per_page'] = $limit;
    $config['reuse_query_string'] = true;
    $config['page_query_string'] = true;
    $config['query_string_segment'] = 'page';

    // bootstrap + tengah
    $config['full_tag_open']  = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open']   = '<li class="page-item">';
    $config['num_tag_close']  = '</li>';
    $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']  = '</span></li>';
    $config['attributes']     = ['class'=>'page-link'];

    $this->pagination->initialize($config);

    $data = [
        'title'      => 'Laporan Kunjungan',
        'kelas'      => $this->db->get('kelas')->result(),
        'jurusan'    => $this->db->get('jurusan')->result(),
        'laporan'    => $this->Kunjungan_model
                                ->get_laporan_paginated($limit, $page, $filter),
        'pagination' => $this->pagination->create_links()
    ];

    $this->_view('laporan/kunjungan', $data);
}

    /* ================= LAPORAN PEMINJAMAN ================= */
    public function peminjaman()
{
    $this->load->library('pagination');

    $awal  = $this->input->get('awal');
    $akhir = $this->input->get('akhir');

    $page  = $this->input->get('page');
    $page  = is_numeric($page) ? $page : 0;
    $limit = 15;

    $total = $this->Peminjaman_model
        ->count_laporan_peminjaman($awal, $akhir);

    // ===== CONFIG PAGINATION =====
    $config['base_url'] = site_url('laporan/peminjaman');
    $config['total_rows'] = $total;
    $config['per_page'] = $limit;
    $config['reuse_query_string'] = true;
    $config['page_query_string'] = true;
    $config['query_string_segment'] = 'page';

    // bootstrap + tengah
    $config['full_tag_open']  = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open']   = '<li class="page-item">';
    $config['num_tag_close']  = '</li>';
    $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']  = '</span></li>';
    $config['attributes']     = ['class'=>'page-link'];

    $this->pagination->initialize($config);

    $data = [
        'title'      => 'Laporan Peminjaman',
        'laporan'    => $this->Peminjaman_model
                                ->get_laporan_peminjaman_paginated(
                                    $limit, $page, $awal, $akhir
                                ),
        'pagination' => $this->pagination->create_links()
    ];

    $this->_view('laporan/peminjaman', $data);
}


    /* ================= LAPORAN TOTAL BUKU ================= */
    public function buku()
{
    $this->load->library('pagination');

    $page  = $this->input->get('page');
    $page  = is_numeric($page) ? $page : 0;
    $limit = 20;

    $total = $this->Buku_fisik_model->count_rekap();

    // pagination config
    $config['base_url'] = site_url('laporan/buku');
    $config['total_rows'] = $total;
    $config['per_page'] = $limit;
    $config['reuse_query_string'] = true;
    $config['page_query_string'] = true;
    $config['query_string_segment'] = 'page';

    // bootstrap tengah
    $config['full_tag_open']  = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open']   = '<li class="page-item">';
    $config['num_tag_close']  = '</li>';
    $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']  = '</span></li>';
    $config['attributes']     = ['class' => 'page-link'];

    $this->pagination->initialize($config);

    $data = [
        'title'      => 'Laporan Buku',
        'buku'       => $this->Buku_fisik_model->get_rekap_paginated($limit, $page),
        'pagination' => $this->pagination->create_links(),
        'offset'     => $page
    ];

    $this->_view('laporan/buku', $data);
}
public function buku_excel()
{
    $data = $this->Buku_fisik_model->get_rekap_paginated(9999, 0);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_buku.xls");

    echo "Judul\tISBN\tPenulis\tPenerbit\tTahun\tStok Awal\tDipinjam\tSisa\n";

    foreach ($data as $b) {
        $sisa = $b->stok_awal - $b->dipinjam;
        echo "{$b->judul}\t{$b->isbn}\t{$b->penulis}\t{$b->penerbit}\t{$b->tahun}\t{$b->stok_awal}\t{$b->dipinjam}\t{$sisa}\n";
    }
}
public function buku_pdf()
{
    $data['buku'] = $this->Buku_fisik_model->get_rekap_paginated(9999, 0);

    $html = $this->load->view('laporan/buku_pdf', $data, true);

    $this->load->library('pdf');
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'landscape');
    $this->pdf->render();
    $this->pdf->stream('laporan_buku.pdf', ['Attachment' => false]);
}


    /* ================= LAPORAN LAINNYA ================= */
    public function lainnya()
    {
        $data = [
            'title' => 'Laporan Lain-lain',
            'data'  => [
                'total_siswa'      => $this->db->count_all('siswa'),
                'total_kunjungan'  => $this->db->count_all('kunjungan'),
                'total_peminjaman' => $this->db->count_all('peminjaman')
            ]
        ];

        $this->_view('laporan/lainnya', $data);
    }

    /* ================= VIEW HELPER ================= */
    private function _view($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $this->data);
        $this->load->view('templates/topbar', $this->data);
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
    public function kunjungan_excel()
{
    $data = $this->Kunjungan_model->filter_laporan($this->input->get());

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_kunjungan.xls");

    echo "Tanggal\tJam\tNIS\tNama\tKelas\tJurusan\tTujuan\n";
    foreach($data as $d){
        echo "{$d->tanggal}\t{$d->jam}\t{$d->nis}\t{$d->nama_siswa}\t{$d->nama_kelas}\t{$d->nama_jurusan}\t{$d->tujuan}\n";
    }
}
public function kunjungan_pdf()
{
    $data['laporan'] = $this->Kunjungan_model->filter_laporan($this->input->get());

    $html = $this->load->view('laporan/kunjungan_pdf', $data, true);

    $this->load->library('pdf');
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'portrait'); // POTRAIT
    $this->pdf->render();
    $this->pdf->stream('laporan_kunjungan.pdf', ['Attachment' => false]);
}

public function peminjaman_excel()
{
    $data = $this->Peminjaman_model->get_laporan(
        $this->input->get('awal'),
        $this->input->get('akhir')
    );

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_peminjaman.xls");

    echo "Tanggal\tNama\tNIS\tKelas\tBuku\tStatus\n";
    foreach ($data as $d) {
        echo "{$d->tanggal_pinjam}\t{$d->nama}\t{$d->nis}\t{$d->nama_kelas}\t{$d->judul}\t{$d->status}\n";
    }
}
public function peminjaman_pdf()
{
    $awal  = $this->input->get('awal');
    $akhir = $this->input->get('akhir');

    $data['awal'] = $awal;
    $data['akhir'] = $akhir;

    $data['laporan'] = $this->Peminjaman_model
        ->get_laporan($awal, $akhir);

    $html = $this->load->view('laporan/peminjaman_pdf', $data, true);

    $this->load->library('pdf');
    $this->pdf->loadHtml($html);
    $this->pdf->setPaper('A4', 'potrait'); // landscape biar lega
    $this->pdf->render();
    $this->pdf->stream(
        'laporan_peminjaman.pdf',
        ['Attachment' => false]
    );
}

}
