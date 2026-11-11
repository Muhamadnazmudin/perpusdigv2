<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Buku_fisik extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Buku_fisik_model');
    }

    public function index()
{
    $this->load->library('pagination');

    $keyword = $this->input->get('q');
    $page    = $this->input->get('page');
    $page    = (is_numeric($page)) ? $page : 0;
    $limit   = 15;

    $total = $this->Buku_fisik_model->count_filtered($keyword);

    $config['base_url'] = site_url('buku-fisik');
    $config['total_rows'] = $total;
    $config['per_page'] = $limit;
    $config['reuse_query_string'] = true;
    $config['page_query_string'] = true;
    $config['query_string_segment'] = 'page';

    // Bootstrap pagination
    $config['full_tag_open'] = '<nav class="d-flex justify-content-center"><ul class="pagination">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['attributes'] = ['class' => 'page-link'];

    $this->pagination->initialize($config);

    $data['title'] = 'Buku Fisik';
    $data['buku']  = $this->Buku_fisik_model
                            ->get_filtered($limit, $page, $keyword);
    $data['pagination'] = $this->pagination->create_links();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar', $this->data);
    $this->load->view('buku_fisik/index', $data);
    $this->load->view('templates/footer');
}

    public function tambah()
{
    $this->load->model(['Kategori_model','Rak_model']);

    $data['title'] = 'Tambah Buku Fisik';
    $data['kategori'] = $this->Kategori_model->get_all();
    $data['rak'] = $this->Rak_model->get_all();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('buku_fisik/tambah', $data);
    $this->load->view('templates/footer');
}

public function simpan()
{
    $isbn = trim($this->input->post('isbn', TRUE));
$isbn = ($isbn === '') ? null : $isbn;

    // ===== CEK ISBN DUPLIKAT =====
    if (!empty($isbn)) {
        if ($this->Buku_fisik_model->isbn_exists($isbn)) {
            $this->session->set_flashdata(
                'error',
                'ISBN sudah terdaftar. Gunakan ISBN lain.'
            );
            redirect('buku_fisik/tambah');
            return;
        }
    }

    $data = [
        'isbn'        => $isbn,
        'judul'       => $this->input->post('judul', TRUE),
        'penulis'     => $this->input->post('penulis', TRUE),
        'penerbit'    => $this->input->post('penerbit', TRUE),
        'tahun'       => $this->input->post('tahun', TRUE),
        'id_kategori' => $this->input->post('id_kategori', TRUE),
        'id_rak'      => $this->input->post('id_rak', TRUE),
        'stok'        => $this->input->post('stok', TRUE),
        'kelas'       => $this->input->post('kelas', TRUE) ?: 'Umum',
    ];

    // ===== UPLOAD COVER =====
    if (!empty($_FILES['cover']['name'])) {

        $config['upload_path']   = './uploads/cover/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('cover')) {
            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors('', '')
            );
            redirect('buku_fisik/tambah');
            return;
        }

        $upload = $this->upload->data();
        $data['cover'] = $upload['file_name'];
    }

    $this->Buku_fisik_model->insert($data);

    $this->session->set_flashdata('success', 'Buku berhasil ditambahkan');
    redirect('buku_fisik');
}


public function edit($id)
{
    $this->load->model(['Kategori_model','Rak_model']);

    $data['title'] = 'Edit Buku Fisik';
    $data['buku'] = $this->Buku_fisik_model->get_by_id($id);
    $data['kategori'] = $this->Kategori_model->get_all();
    $data['rak'] = $this->Rak_model->get_all();

    if (!$data['buku']) {
        show_404();
    }

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('buku_fisik/edit', $data);
    $this->load->view('templates/footer');
}
public function update($id)
{
    $buku = $this->Buku_fisik_model->get_by_id($id);

    if (!$buku) {
        show_404();
    }

    $isbn = trim($this->input->post('isbn', TRUE));
$isbn = ($isbn === '') ? null : $isbn;

    // ===== CEK ISBN DUPLIKAT (KECUALI DIRI SENDIRI) =====
    if (!empty($isbn)) {
        if ($this->Buku_fisik_model->isbn_exists($isbn, $id)) {
            $this->session->set_flashdata(
                'error',
                'ISBN sudah digunakan oleh buku lain.'
            );
            redirect('buku_fisik/edit/'.$id);
            return;
        }
    }

    $data = [
        'isbn'        => $isbn,
        'judul'       => $this->input->post('judul', TRUE),
        'penulis'     => $this->input->post('penulis', TRUE),
        'penerbit'    => $this->input->post('penerbit', TRUE),
        'tahun'       => $this->input->post('tahun', TRUE),
        'id_kategori' => $this->input->post('id_kategori', TRUE),
        'id_rak'      => $this->input->post('id_rak', TRUE),
        'stok'        => $this->input->post('stok', TRUE),
        'kelas'       => $this->input->post('kelas', TRUE) ?: 'Umum',
    ];

    // ===== UPLOAD COVER BARU =====
    if (!empty($_FILES['cover']['name'])) {

        $config['upload_path']   = './uploads/cover/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('cover')) {
            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors('', '')
            );
            redirect('buku_fisik/edit/'.$id);
            return;
        }

        // hapus cover lama
        if ($buku->cover && file_exists('./uploads/cover/'.$buku->cover)) {
            unlink('./uploads/cover/'.$buku->cover);
        }

        $upload = $this->upload->data();
        $data['cover'] = $upload['file_name'];
    }

    $this->Buku_fisik_model->update($id, $data);

    $this->session->set_flashdata('success', 'Buku berhasil diperbarui');
    redirect('buku_fisik');
}


public function hapus($id)
{
    $buku = $this->Buku_fisik_model->get_by_id($id);

    if (!$buku) {
        show_404();
    }

    $this->Buku_fisik_model->delete($id);

    $this->session->set_flashdata('success', 'Buku berhasil dihapus');
    redirect('buku-fisik');
}
public function detail($id)
{
    $data['buku'] = $this->Buku_fisik_model->get_detail($id);

    if (!$data['buku']) {
        show_404();
    }

    $data['title'] = 'Detail Buku Fisik';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('buku_fisik/detail', $data);
    $this->load->view('templates/footer');
}
public function import()
{
    if (empty($_FILES['file_excel']['name'])) {
        $this->session->set_flashdata('error', 'File Excel wajib diupload');
        redirect('buku_fisik/tambah');
        return;
    }

    // ===== UPLOAD FILE EXCEL =====
    $config['upload_path']   = './uploads/excel/';
    $config['allowed_types'] = 'xls|xlsx';
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file_excel')) {
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        redirect('buku_fisik/tambah');
        return;
    }

    $file = $this->upload->data();

    // ===== LOAD PHPSPREADSHEET =====
    require FCPATH . 'vendor/autoload.php';

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file['full_path']);
    $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $default_kategori = $this->input->post('default_kategori');
    $default_rak      = $this->input->post('default_rak');

    $insert = [];
    $inserted = 0;
    $skipped  = 0;

    foreach ($rows as $i => $row) {

        // skip header
        if ($i == 1) {
            continue;
        }

        $isbn = trim($row['A']);
        $isbn = ($isbn === '') ? null : $isbn;

        // skip jika judul kosong
        if (empty($row['B'])) {
            continue;
        }

        // cek ISBN duplikat
        if (!empty($isbn)) {
            if ($this->Buku_fisik_model->isbn_exists($isbn)) {
                $skipped++;
                continue;
            }
        }

        // ambil kelas dari excel kolom I
        $kelas = !empty($row['I']) ? trim($row['I']) : 'Umum';

        $insert[] = [
            'isbn'        => $isbn,
            'judul'       => trim($row['B']),
            'penulis'     => trim($row['C']),
            'penerbit'    => trim($row['D']),
            'tahun'       => trim($row['E']),
            'id_kategori' => !empty($row['F']) ? $row['F'] : $default_kategori,
            'id_rak'      => !empty($row['G']) ? $row['G'] : $default_rak,
            'stok'        => !empty($row['H']) ? $row['H'] : 1,
            'kelas'       => $kelas,
            'created_at'  => date('Y-m-d H:i:s')
        ];
    }

    // ===== INSERT KE DB =====
    if (!empty($insert)) {
        $this->Buku_fisik_model->insert_batch($insert);
        $inserted = count($insert);
    }

    // hapus file excel
    unlink($file['full_path']);

    // ===== FLASH MESSAGE =====
    $this->session->set_flashdata(
        'success',
        "Import selesai. Berhasil: {$inserted} data, Dilewati (ISBN duplikat): {$skipped} data."
    );

    redirect('buku_fisik');
}
public function import_cover()
{
    if (empty($_FILES['file_zip']['name'])) {
        $this->session->set_flashdata('error', 'File ZIP wajib diupload');
        redirect('buku_fisik');
        return;
    }

    // ===== UPLOAD ZIP =====
    $config['upload_path']   = './uploads/zip/';
    $config['allowed_types'] = 'zip';
    $config['encrypt_name']  = TRUE;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('file_zip')) {
        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        redirect('buku_fisik');
        return;
    }

    $file = $this->upload->data();
    $zip_path = $file['full_path'];

    // ===== EXTRACT ZIP =====
    $extract_path = './uploads/temp_cover/';
    if (!is_dir($extract_path)) {
        mkdir($extract_path, 0777, true);
    }

    $zip = new ZipArchive;
    if ($zip->open($zip_path) === TRUE) {
        $zip->extractTo($extract_path);
        $zip->close();
    } else {
        $this->session->set_flashdata('error', 'Gagal extract ZIP');
        redirect('buku_fisik');
        return;
    }

    // ===== PROSES FILE =====
    $files = scandir($extract_path);

    $success = 0;
    $failed  = 0;

    foreach ($files as $file_name) {
        if ($file_name == '.' || $file_name == '..') continue;

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $id_buku = pathinfo($file_name, PATHINFO_FILENAME);

        // validasi ekstensi
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            $failed++;
            continue;
        }

        // cek buku ada
        $buku = $this->Buku_fisik_model->get_by_id($id_buku);
        if (!$buku) {
            $failed++;
            continue;
        }

        $source = $extract_path . $file_name;
        $new_name = $id_buku . '.' . $ext;
        $destination = './uploads/cover/' . $new_name;

        // hapus cover lama jika ada
        if (!empty($buku->cover) && file_exists('./uploads/cover/'.$buku->cover)) {
            unlink('./uploads/cover/'.$buku->cover);
        }

        rename($source, $destination);

        // update DB
        $this->Buku_fisik_model->update($id_buku, [
            'cover' => $new_name
        ]);

        $success++;
    }

    // hapus file zip
    unlink($zip_path);

    // bersihkan folder temp
    array_map('unlink', glob($extract_path . "*"));

    $this->session->set_flashdata(
        'success',
        "Upload cover selesai. Berhasil: {$success}, Gagal: {$failed}"
    );

    redirect('buku_fisik');
}
}
