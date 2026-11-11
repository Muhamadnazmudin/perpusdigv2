<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Peminjaman extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'Peminjaman_model',
            'Buku_fisik_model'
        ]);
    }

    // =======================
    // LIST PEMINJAMAN
    // =======================
    public function index()
{
    $this->only_role([1]);

    $this->load->library('pagination');
    $this->load->model('Kelas_model');

    $kelas   = $this->input->get('kelas');
    $keyword = $this->input->get('q');
    $page    = $this->input->get('page') ?? 0;
    $limit   = 10;

    $total = $this->Peminjaman_model
        ->count_filtered($kelas, $keyword);

    $config['base_url']   = site_url('peminjaman');
    $config['total_rows'] = $total;
    $config['per_page']   = $limit;
    $config['reuse_query_string'] = true;
    $config['page_query_string'] = true;
    $config['query_string_segment'] = 'page';

    // Bootstrap pagination
    $config['full_tag_open']  = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['attributes'] = ['class' => 'page-link'];

    $this->pagination->initialize($config);

    $data = [
        'title'           => 'Peminjaman Buku',
        'peminjaman'      => $this->Peminjaman_model
                                ->get_filtered($limit, $page, $kelas, $keyword),
        'total_menunggu'  => $this->Peminjaman_model->count_menunggu(),
        'kelas'           => $this->Kelas_model->get_all(),
        'pagination'      => $this->pagination->create_links()
    ];

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar', $this->data);
    $this->load->view('peminjaman/index',$data);
    $this->load->view('templates/footer');
}
// =======================
// FORM TAMBAH PINJAMAN (ADMIN)
// =======================
public function tambah()
{
    $this->only_role([1]); // ADMIN

    $this->load->model([
        'User_model',
        'Buku_fisik_model'
    ]);

    $data = [
        'title' => 'Tambah Peminjaman',
        'siswa' => $this->User_model->get_siswa(),
        'buku'  => $this->Buku_fisik_model->get_ready()
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar', $this->data);
    $this->load->view('peminjaman/tambah', $data);
    $this->load->view('templates/footer');
}


    // =======================
    // SIMPAN PINJAM
    // =======================
    public function simpan()
{
    $this->only_role([1]);

    $id_user = $this->input->post('id_user', TRUE);
    $id_buku = $this->input->post('id_buku', TRUE);

    // 🔒 CEK LIMIT SISWA
    $total = $this->Peminjaman_model->count_pinjam_aktif($id_user);
    if ($total >= $this->config->item('max_pinjam')) {
        $this->session->set_flashdata(
            'error',
            'Siswa ini sudah mencapai limit peminjaman (maks '.$this->config->item('max_pinjam').' buku)'
        );
        redirect('peminjaman/tambah');
        return;
    }

    $buku = $this->Buku_fisik_model->get_by_id($id_buku);
    if (!$buku || $buku->stok < 1) {
        $this->session->set_flashdata('error','Stok buku habis');
        redirect('peminjaman/tambah');
        return;
    }

    $this->Peminjaman_model->insert([
        'id_user'             => $id_user,
        'id_buku'             => $id_buku,
        'tanggal_pinjam'      => date('Y-m-d'),
        'tanggal_jatuh_tempo' => date('Y-m-d', strtotime('+6 days')), /*ngarobah waktu pinjam +7 artina 7poe*/
        'status'              => 'dipinjam'
    ]);

    $this->db
        ->where('id_buku',$id_buku)
        ->set('stok','stok-1',FALSE)
        ->update('buku_fisik');

    $this->session->set_flashdata('success','Peminjaman berhasil ditambahkan');
    redirect('peminjaman');
}

public function setujui($id)
{
    $this->only_role([1]); // ADMIN

    $pinjam = $this->Peminjaman_model->get_by_id($id);
    if (!$pinjam || $pinjam->status !== 'menunggu') {
        show_404();
    }

    // cek stok buku
    $buku = $this->Buku_fisik_model->get_by_id($pinjam->id_buku);
    if ($buku->stok < 1) {
        $this->session->set_flashdata('error', 'Stok buku habis');
        redirect('peminjaman');
        return;
    }

    // kurangi stok
    $this->db
        ->where('id_buku', $pinjam->id_buku)
        ->set('stok', 'stok-1', FALSE)
        ->update('buku_fisik');

    // update status jadi dipinjam
    $this->Peminjaman_model->update($id, [
        'status' => 'dipinjam',
        'tanggal_jatuh_tempo' => date('Y-m-d', strtotime('+6 days'))
    ]);

    $this->session->set_flashdata('success', 'Peminjaman berhasil disetujui');
    redirect('peminjaman');
}
public function tolak($id)
{
    $this->only_role([1]); // ADMIN

    $pinjam = $this->Peminjaman_model->get_by_id($id);
    if (!$pinjam || $pinjam->status !== 'menunggu') {
        show_404();
    }

    $this->Peminjaman_model->update($id, [
        'status' => 'ditolak'
    ]);

    $this->session->set_flashdata('success', 'Pengajuan peminjaman ditolak');
    redirect('peminjaman');
}

    // =======================
    // KEMBALIKAN BUKU
    // =======================
    public function kembali($id)
    {
        $this->only_role([1]);
        $pinjam = $this->Peminjaman_model->get_by_id($id);

        if (!$pinjam || $pinjam->status !== 'dipinjam') {
            show_404();
        }

        // update peminjaman
        $this->Peminjaman_model->update($id,[
            'status' => 'kembali',
            'tanggal_kembali' => date('Y-m-d')
        ]);

        // tambah stok buku
        $this->db
            ->where('id_buku',$pinjam->id_buku)
            ->set('stok','stok+1',FALSE)
            ->update('buku_fisik');

        $this->session->set_flashdata('success','Buku berhasil dikembalikan');
        redirect('peminjaman');
    }
    public function pinjam($id_buku)
{
    $this->only_role([2,3]); // SISWA

    $id_user = $this->user['id_user'];

    // 🔒 CEK LIMIT (HANYA HITUNG YANG SUDAH DIPINJAM)
    $total = $this->Peminjaman_model->count_pinjam_aktif($id_user);
    if ($total >= $this->config->item('max_pinjam')) {
        $this->session->set_flashdata(
            'error',
            'Limit peminjaman tercapai (maksimal '.$this->config->item('max_pinjam').' buku)'
        );
        redirect('buku/detail/'.$id_buku);
        return;
    }

    // 🔍 CEK BUKU
    $buku = $this->Buku_fisik_model->get_by_id($id_buku);
    if (!$buku || $buku->stok < 1) {
        $this->session->set_flashdata('error','Stok buku habis');
        redirect('buku/detail/'.$id_buku);
        return;
    }

    // 🚫 CEGAH AJUKAN BUKU YANG SAMA (MENUNGGU / DIPINJAM)
    if ($this->Peminjaman_model->cek_pinjam_menunggu_atau_aktif($id_user, $id_buku)) {
        $this->session->set_flashdata(
            'error',
            'Buku ini sudah kamu ajukan atau masih dipinjam'
        );
        redirect('buku/detail/'.$id_buku);
        return;
    }

    // 💾 SIMPAN PENGAJUAN (STATUS: MENUNGGU)
    $this->Peminjaman_model->insert([
        'id_user'        => $id_user,
        'id_buku'        => $id_buku,
        'tanggal_pinjam' => date('Y-m-d'),
        'status'         => 'menunggu'
    ]);

    $this->session->set_flashdata(
        'success',
        'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.'
    );

    redirect('peminjaman/daftar');
}
public function daftar()
{
    $this->only_role([2,3]); // SISWA

    $data['title'] = 'Daftar Pinjaman Saya';
    $data['peminjaman'] = $this->Peminjaman_model
    ->get_by_user($this->user['id_user']);
    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('peminjaman/daftar',$data);
    $this->load->view('templates/footer');
}

public function riwayat()
{
    $this->only_role([2,3]);

    $data['title'] = 'Riwayat Pinjaman';
    $data['peminjaman'] = $this->Peminjaman_model
        ->get_riwayat_by_user($this->user['id_user']);

    $this->load->view('templates/header',$data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('peminjaman/riwayat',$data);
    $this->load->view('templates/footer');
}

}
