<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AdminEbook extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1,2]); // ADMIN & GURU
        $this->load->model('Ebook_model');
        $this->load->library('upload'); // 🔑 WAJIB
    }

    /* ===================== INDEX ===================== */
public function index()
{
    $this->load->library('pagination');

    $keyword = $this->input->get('q');
    $kelas   = $this->input->get('kelas');
    $mapel   = $this->input->get('mapel');

    $page  = is_numeric($this->input->get('page')) ? (int)$this->input->get('page') : 0;
    $limit = 15;

    // ADMIN → JANGAN pakai filter publik
    $total = $this->Ebook_model->count_all_admin($keyword, $kelas, $mapel);

    $config = [
        'base_url'            => site_url('AdminEbook'),
        'total_rows'          => $total,
        'per_page'            => $limit,
        'page_query_string'   => true,
        'query_string_segment'=> 'page',
        'reuse_query_string'  => true,

        // ===== BOOTSTRAP PAGINATION =====
        'full_tag_open'       => '<nav><ul class="pagination justify-content-center">',
        'full_tag_close'      => '</ul></nav>',

        'num_tag_open'        => '<li class="page-item">',
        'num_tag_close'       => '</li>',

        'cur_tag_open'        => '<li class="page-item active"><span class="page-link">',
        'cur_tag_close'       => '</span></li>',

        'prev_tag_open'       => '<li class="page-item">',
        'prev_tag_close'      => '</li>',
        'prev_link'           => '&laquo;',

        'next_tag_open'       => '<li class="page-item">',
        'next_tag_close'      => '</li>',
        'next_link'           => '&raquo;',

        'attributes'          => ['class' => 'page-link']
    ];

    $this->pagination->initialize($config);

    $data = [
        'title'      => 'Manajemen E-Book',
        'ebook'      => $this->Ebook_model->get_all_admin(
                            $limit, $page, $keyword, $kelas, $mapel
                        ),
        'pagination' => $this->pagination->create_links()
    ];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar', $this->data);
    $this->load->view('admin/ebook/index', $data);
    $this->load->view('templates/footer');
}

    /* ===================== TAMBAH ===================== */
    public function tambah()
    {
        $data['title'] = 'Tambah E-Book';
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/ebook/tambah',$data);
        $this->load->view('templates/footer');
    }

    /* ===================== SIMPAN ===================== */
    public function simpan()
    {
        $judul  = trim($this->input->post('judul', true));
        $mapel  = trim($this->input->post('mapel', true));
        $kelas  = strtoupper(trim($this->input->post('kelas', true)));
        $source = $this->input->post('source', true);

        if ($judul === '' || $kelas === '' || !in_array($kelas, ['X','XI','XII','UMUM'])) {
            $this->session->set_flashdata('error','Data tidak valid');
            redirect('AdminEbook/tambah');
            return;
        }

        $data = [
            'judul'      => $judul,
            'mapel'      => $mapel,
            'kelas'      => $kelas,
            'source'     => $source,
            'cover'      => null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        /* ===== DRIVE ===== */
        if ($source === 'DRIVE') {
            $drive_id = trim($this->input->post('drive_link', true));
            if ($drive_id === '') {
                $this->session->set_flashdata('error','File ID Google Drive wajib diisi');
                redirect('AdminEbook/tambah');
                return;
            }
            $data['file_drive'] = $drive_id;
            $data['file_local'] = null;
        }

        /* ===== LOCAL ===== */
        if ($source === 'LOCAL') {
            if (empty($_FILES['file_local']['name'])) {
                $this->session->set_flashdata('error','File ebook wajib diupload');
                redirect('AdminEbook/tambah');
                return;
            }
            $data['file_local'] = $this->_upload_ebook();
            $data['file_drive'] = null;
        }

        /* ===== COVER ===== */
        if (!empty($_FILES['cover']['name'])) {
            $data['cover'] = $this->_upload_cover();
            if ($data['cover'] === false) return;
        }

        $this->db->insert('ebook', $data);

        $this->session->set_flashdata('success','E-Book berhasil ditambahkan');
        redirect('AdminEbook');
    }
    private function extract_drive_id($input)
{
    if (!$input) return null;

    $input = trim($input);

    // jika sudah ID murni
    if (preg_match('/^[a-zA-Z0-9_-]{20,}$/', $input)) {
        return $input;
    }

    // format /file/d/ID/
    if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $input, $m)) {
        return $m[1];
    }

    // format id=ID
    if (preg_match('/id=([a-zA-Z0-9_-]+)/', $input, $m)) {
        return $m[1];
    }

    return null;
}

public function import()
{
    if ($this->input->method() !== 'post') show_404();

    require FCPATH.'vendor/autoload.php';

    if (empty($_FILES['file']['name'])) {
        $this->session->set_flashdata('error','File belum dipilih');
        redirect('AdminEbook');
        return;
    }

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, ['xlsx','csv'])) {
        $this->session->set_flashdata('error','File harus Excel (.xlsx / .csv)');
        redirect('AdminEbook');
        return;
    }

    $reader = ($ext === 'xlsx')
        ? new \PhpOffice\PhpSpreadsheet\Reader\Xlsx()
        : new \PhpOffice\PhpSpreadsheet\Reader\Csv();

    $rows = $reader
        ->load($_FILES['file']['tmp_name'])
        ->getActiveSheet()
        ->toArray();

    $insert = [];
    $skip   = 0;

    foreach ($rows as $i => $row) {

        // skip header
        if ($i === 0) continue;

        // judul wajib
        if (empty($row[0])) {
            $skip++;
            continue;
        }

        // ekstrak ID drive
        $drive_id = $this->extract_drive_id($row[3] ?? '');

        if (!$drive_id) {
            $skip++;
            continue;
        }

        $insert[] = [
            'judul'      => trim($row[0]),
            'mapel'      => trim($row[1] ?? ''),
            'kelas'      => strtoupper(trim($row[2] ?? 'UMUM')),
            'source'     => 'DRIVE',
            'file_drive' => $drive_id,
            'status'     => 'APPROVED',
            'is_public'  => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    if ($insert) {
        $this->db->insert_batch('ebook', $insert);
    }

    $this->session->set_flashdata(
        'success',
        'Import selesai. Berhasil: '.count($insert).' | Dilewati: '.$skip
    );

    redirect('AdminEbook');
}


    /* ===================== EDIT ===================== */
    public function edit($id)
    {
        $ebook = $this->Ebook_model->get_by_id($id);
        if (!$ebook) show_404();

        $data = [
            'title' => 'Edit E-Book',
            'ebook' => $ebook
        ];

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/ebook/edit',$data);
        $this->load->view('templates/footer');
    }

    /* ===================== UPDATE ===================== */
    public function update($id)
{
    $ebook = $this->Ebook_model->get_by_id($id);
    if (!$ebook) show_404();

    $source = $this->input->post('source');

    $data = [
        'judul' => $this->input->post('judul', true),
        'mapel' => $this->input->post('mapel', true),
        'kelas' => strtoupper($this->input->post('kelas', true)),
        'source'=> $source
    ];

    /* ===== DRIVE ===== */
    if ($source === 'DRIVE') {
        $data['file_drive'] = trim($this->input->post('drive_link', true));

        // hapus file local lama
        if ($ebook->source === 'LOCAL' && $ebook->file_local) {
            $file = FCPATH.'assets/uploads/ebook/'.$ebook->file_local;
            if (file_exists($file)) unlink($file);
        }

        $data['file_local'] = null;
    }

    /* ===== LOCAL ===== */
    if ($source === 'LOCAL' && !empty($_FILES['file_local']['name'])) {

        // hapus file lama
        if ($ebook->file_local) {
            $old = FCPATH.'assets/uploads/ebook/'.$ebook->file_local;
            if (file_exists($old)) unlink($old);
        }

        $data['file_local'] = $this->_upload_ebook();
        $data['file_drive'] = null;
    }

    /* ===== COVER ===== */
    if (!empty($_FILES['cover']['name'])) {
        if ($ebook->cover) {
            $old = FCPATH.'assets/uploads/cover_ebook/'.$ebook->cover;
            if (file_exists($old)) unlink($old);
        }
        $data['cover'] = $this->_upload_cover();
    }

    $this->db->where('id_ebook', $id)->update('ebook', $data);

    $this->session->set_flashdata('success','E-Book berhasil diperbarui');
    redirect('AdminEbook');
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
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            redirect('AdminEbook/tambah');
            return false;
        }

        return $this->upload->data('file_name');
    }

    /* ===================== UPLOAD EBOOK ===================== */
    private function _upload_ebook()
    {
        $config = [
            'upload_path'   => './assets/uploads/ebook/',
            'allowed_types' => 'pdf',
            'max_size'      => 10240,
            'encrypt_name'  => true
        ];

        $this->upload->initialize($config, true);

        if (!$this->upload->do_upload('file_local')) {
            $this->session->set_flashdata('error', strip_tags($this->upload->display_errors()));
            redirect('AdminEbook/tambah');
            exit;
        }

        return $this->upload->data('file_name');
    }
    /* ===================== DELETE ===================== */
public function delete($id)
{
    $ebook = $this->Ebook_model->get_by_id($id);
    if (!$ebook) {
        show_404();
    }

    // 🔥 hapus file ebook local jika ada
    if ($ebook->source === 'LOCAL' && !empty($ebook->file_local)) {
        $path = FCPATH . 'assets/uploads/ebook/' . $ebook->file_local;
        if (file_exists($path)) {
            unlink($path);
        }
    }

    // 🔥 hapus cover jika ada
    if (!empty($ebook->cover)) {
        $cover = FCPATH . 'assets/uploads/cover_ebook/' . $ebook->cover;
        if (file_exists($cover)) {
            unlink($cover);
        }
    }

    // 🔥 hapus data DB
    $this->db->where('id_ebook', $id)->delete('ebook');

    $this->session->set_flashdata('success','E-Book berhasil dihapus');
    redirect('AdminEbook');
}

}
