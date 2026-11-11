<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


class Siswa extends MY_Controller {

    private $per_page = 20;

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);

        $this->load->model([
            'Siswa_model',
            'Kelas_model',
            'Jurusan_model'
        ]);

        $this->load->library('pagination');

        // phpqrcode (load sekali)
        require_once APPPATH . 'libraries/phpqrcode/phpqrcode.php';
    }

    /* =========================================================
     * INDEX + PAGINATION
     * ========================================================= */
    public function index($offset = 0)
{
    $this->load->library('pagination');

    // ====== FILTER ======
    $kelas   = $this->input->get('kelas');
    $keyword = $this->input->get('keyword');

    // ====== TOTAL DATA ======
    $total = $this->Siswa_model->count_filtered($kelas, $keyword);

    $config['base_url']            = site_url('siswa/index');
    $config['total_rows']          = $total;
    $config['per_page']            = $this->per_page;
    $config['uri_segment']         = 3;
    $config['reuse_query_string']  = TRUE;

    // ====== BOOTSTRAP PAGINATION ======
    $config['full_tag_open']  = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['num_tag_open']   = '<li class="page-item">';
    $config['num_tag_close']  = '</li>';
    $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']  = '</span></li>';
    $config['attributes']     = ['class' => 'page-link'];

    $this->pagination->initialize($config);

    // ====== DATA ======
    $data['title']   = 'Data Siswa';
    $data['siswa']   = $this->Siswa_model->get_filtered(
        $this->per_page,
        $offset,
        $kelas,
        $keyword
    );
    $data['kelas']   = $this->Kelas_model->get_all();
    $data['jurusan'] = $this->Jurusan_model->get_all();
    $data['pagination'] = $this->pagination->create_links();

    // ====== LOAD VIEW (JANGAN DIUBAH) ======
    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('siswa/index', $data);
    $this->load->view('templates/footer');
}

    /* =========================================================
     * TAMBAH SISWA
     * ========================================================= */
    public function tambah()
{
    $nis        = $this->input->post('nis');
    $nama       = $this->input->post('nama_siswa');
    $id_kelas   = $this->input->post('id_kelas');
    $id_jurusan = $this->input->post('id_jurusan');

    // ===== NO HP =====
    $no_hp = preg_replace('/[^0-9]/', '', $this->input->post('no_hp'));
    if (substr($no_hp, 0, 1) == '0') {
        $no_hp = '62' . substr($no_hp, 1);
    }

    // ===== RFID =====
    $rfid_uid = trim($this->input->post('rfid_uid'));
    $rfid_uid = $rfid_uid === '' ? null : $rfid_uid;
        // 🔒 CEK NIS DOBEL (WAJIB)
    if ($this->db->get_where('siswa', ['nis' => $nis])->row()) {
        $this->session->set_flashdata('error', 'NIS sudah terdaftar');
        redirect('siswa');
        return;
    }


    // 🔒 cek RFID dobel
    if ($rfid_uid) {
        if ($this->db->get_where('siswa', ['rfid_uid' => $rfid_uid])->row()) {
            $this->session->set_flashdata('error', 'RFID sudah digunakan siswa lain');
            redirect('siswa');
            return;
        }
    }

    // ===== INSERT SISWA =====
    $this->db->insert('siswa', [
    'nis'        => $nis,
    'nama_siswa' => $nama,
    'no_hp'      => $no_hp,
    'id_kelas'   => $id_kelas,
    'id_jurusan' => $id_jurusan,
    'rfid_uid'   => $rfid_uid,
    'status'     => 'aktif'
]);


    $id_siswa = $this->db->insert_id();

    // ===== GENERATE QR =====
    $qr = $this->generate_qr_siswa($nis);

    $this->db->where('id_siswa', $id_siswa)->update('siswa', [
        'qr_code'  => $qr['path'],
        'qr_token' => $qr['token']
    ]);

    // ===== AKUN LOGIN =====
    $this->db->insert('users', [
        'id_role'  => 3,
        'username' => $nis,
        'password' => password_hash($nis, PASSWORD_DEFAULT),
        'nama'     => $nama,
        'status'   => 'aktif'
    ]);

    $this->session->set_flashdata('success', 'Siswa berhasil ditambahkan');
    redirect('siswa');
}


    /* =========================================================
     * EDIT SISWA (QR TIDAK DIUBAH)
     * ========================================================= */
    public function edit()
{
    $id         = $this->input->post('id_siswa');
    $nis        = $this->input->post('nis');
    $nama       = $this->input->post('nama_siswa');
    $id_kelas   = $this->input->post('id_kelas');
    $id_jurusan = $this->input->post('id_jurusan');

    // ===== NO HP =====
    $no_hp = preg_replace('/[^0-9]/', '', $this->input->post('no_hp'));
    if (substr($no_hp, 0, 1) == '0') {
        $no_hp = '62' . substr($no_hp, 1);
    }

    // ===== RFID =====
    $rfid_uid = trim($this->input->post('rfid_uid'));
    $rfid_uid = $rfid_uid === '' ? null : $rfid_uid;

    // 🔒 cek RFID dobel (kecuali milik sendiri)
    if ($rfid_uid) {
        $this->db->where('rfid_uid', $rfid_uid);
        $this->db->where('id_siswa !=', $id);
        if ($this->db->get('siswa')->row()) {
            $this->session->set_flashdata('error', 'RFID sudah digunakan siswa lain');
            redirect('siswa');
            return;
        }
    }

    // ===== UPDATE SISWA =====
    $this->db->where('id_siswa', $id)->update('siswa', [
        'nis'        => $nis,
        'nama_siswa' => $nama,
        'no_hp'      => $no_hp,
        'id_kelas'   => $id_kelas,
        'id_jurusan' => $id_jurusan,
        'rfid_uid'   => $rfid_uid
    ]);

    $this->session->set_flashdata('success', 'Data siswa berhasil diperbarui');
    redirect('siswa');
}

    /* =========================================================
     * HAPUS SISWA
     * ========================================================= */
    public function hapus($id)
    {
        $siswa = $this->db->get_where('siswa', ['id_siswa' => $id])->row();

        if ($siswa && $siswa->qr_code && file_exists(FCPATH.$siswa->qr_code)) {
            unlink(FCPATH.$siswa->qr_code);
        }

        $this->db->delete('siswa', ['id_siswa' => $id]);
        $this->db->delete('users', ['username' => $siswa->nis]);

        $this->session->set_flashdata('success', 'Siswa berhasil dihapus');
        redirect('siswa');
    }

    /* =========================================================
     * IMPORT SISWA (CSV / XLSX – TANPA REGENERATE QR)
     * ========================================================= */
    public function import()
{
    $this->only_role([1]); // ADMIN

    if (empty($_FILES['file']['name'])) {
        $this->session->set_flashdata('error','File belum dipilih');
        redirect('siswa');
        return;
    }

    require FCPATH.'vendor/autoload.php';

    $file = $_FILES['file']['tmp_name'];
    $ext  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if ($ext !== 'xlsx') {
        $this->session->set_flashdata('error','File harus Excel (.xlsx)');
        redirect('siswa');
        return;
    }

    $reader = new Xlsx();
    $sheet  = $reader->load($file)->getActiveSheet()->toArray();

    $insert = 0;
    $update = 0;
    $error  = [];

    foreach ($sheet as $i => $row) {

        if ($i == 0) continue; // header
        $baris = $i + 1;

        if (empty($row[0]) || empty($row[1])) {
            $error[] = "Baris {$baris}: NIS / Nama kosong";
            continue;
        }

        $nis        = trim((string)$row[0]);
        $nama       = trim($row[1]);
        $no_hp      = trim((string)$row[2]);
        $id_kelas   = trim($row[3]);
        $id_jurusan = trim($row[4]);

        if ($id_kelas === '') {
            $error[] = "Baris {$baris}: ID Kelas kosong";
            continue;
        }

        if (!is_numeric($id_kelas)) {
            $error[] = "Baris {$baris}: ID Kelas tidak valid";
            continue;
        }

        $id_kelas = (int)$id_kelas;
        $id_jurusan = (int)$id_jurusan;

        // 🔒 CEK KELAS
        if (!$this->db->get_where('kelas', ['id_kelas'=>$id_kelas])->row()) {
            $error[] = "Baris {$baris}: ID Kelas ({$id_kelas}) tidak tersedia";
            continue;
        }

        // 🔒 CEK JURUSAN
        if (!$this->db->get_where('jurusan', ['id_jurusan'=>$id_jurusan])->row()) {
            $error[] = "Baris {$baris}: ID Jurusan ({$id_jurusan}) tidak tersedia";
            continue;
        }

        // skip jika NIS sudah ada
        $exist = $this->db->get_where('siswa', ['nis' => $nis])->row();

$dataImport = [
    'nama_siswa' => $nama,
    'no_hp'      => $no_hp,
    'id_kelas'   => $id_kelas,
    'id_jurusan' => $id_jurusan,
    'status'     => 'aktif'
];

if ($exist) {

    // ===============================
    // CEK APAKAH ADA PERUBAHAN DATA
    // ===============================
    $changed = false;
    foreach ($dataImport as $k => $v) {
        if ((string)$exist->$k !== (string)$v) {
            $changed = true;
            break;
        }
    }

    // 🔁 UPDATE JIKA ADA PERUBAHAN
    if ($changed) {
        $this->db->where('id_siswa', $exist->id_siswa)
                 ->update('siswa', $dataImport);
        $update++;
    }

    // ⏭️ JIKA SAMA → SKIP
    continue;
}


        // normalisasi no HP (AMAN)
        $no_hp = preg_replace('/[^0-9]/', '', $no_hp);
        if (substr($no_hp, 0, 1) === '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }

        // INSERT (TETAP SEPERTI ASLI)
        $this->db->insert('siswa', [
    'nis'        => $nis,
    'nama_siswa' => $nama,
    'no_hp'      => $no_hp,
    'id_kelas'   => $id_kelas,
    'id_jurusan' => $id_jurusan,
    'status'     => 'aktif'   // 🔥 WAJIB
]);

        $id_siswa = $this->db->insert_id();

        // generate QR (TETAP)
        $qr = $this->generate_qr_siswa($nis);

        $this->db->where('id_siswa', $id_siswa)
                 ->update('siswa', [
                     'qr_code'  => $qr['path'],
                     'qr_token' => $qr['token']
                 ]);

        // akun login (TETAP)
        $this->db->insert('users', [
            'id_role'  => 3,
            'username' => $nis,
            'password' => password_hash($nis, PASSWORD_DEFAULT),
            'nama'     => $nama,
            'status'   => 'aktif'
        ]);

        $insert++;
    }

    // PESAN notip
    $msg = "Import siswa selesai.<br>✔ {$insert} data berhasil";

    if (!empty($error)) {
        $msg .= "<br><br><strong>❌ Data gagal:</strong><br>";
        $msg .= implode('<br>', array_slice($error, 0, 10));
    }

    $this->session->set_flashdata('success', $msg);
    redirect('siswa');
}



    /* =========================================================
     * GENERATE QR (PRIVATE)
     * ========================================================= */
    private function generate_qr_siswa($nis)
{
    $dir = FCPATH . 'assets/qrcode/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    // 🔥 ISI QR = NIS (yang = NISN)
    $file = $nis . '.png';

    if (!file_exists($dir . $file)) {
        QRcode::png(
            $nis,
            $dir . $file,
            QR_ECLEVEL_H,
            6,
            2
        );
    }

    return [
        'path'  => 'assets/qrcode/' . $file,
        'token' => $nis
    ];
}

/* =========================================================
 * KENAIKAN KELAS
 * ========================================================= */
public function kenaikan_kelas()
{
    $this->only_role([1]);

    $data['title'] = 'Kenaikan Kelas';

    // ambil kelas (dropdown atas)
    $data['kelas'] = $this->db->get('kelas')->result();

    // 🔥 INI PENTING
    $data['siswa'] = [];        // default kosong
    $data['kelas_tujuan'] = []; // default kosong

    // kalau ada kelas dipilih
    $id_kelas = $this->input->get('kelas');
    if ($id_kelas) {

        // ambil siswa di kelas terpilih
        $data['siswa'] = $this->db->query("
            SELECT s.*, k.nama_kelas
            FROM siswa s
            JOIN kelas k ON k.id_kelas = s.id_kelas
            WHERE s.id_kelas = ?
            AND s.status = 'aktif'
        ", [$id_kelas])->result();

        // tentukan kelas tujuan (contoh: X -> XI)
        $data['kelas_tujuan'] = $this->db->query("
            SELECT * FROM kelas
            WHERE nama_kelas LIKE 'XI %'
        ")->result();
    }

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('siswa/kenaikan_kelas', $data);
    $this->load->view('templates/footer');
}

/* =========================================================
 * PROSES KENAIKAN KELAS
 * ========================================================= */
public function proses_kenaikan_kelas()
{
    $this->only_role([1]);

    $map_x  = $this->input->post('map_x');   // X -> XI
    $map_xi = $this->input->post('map_xi');  // XI -> XII

    if (!$map_x && !$map_xi) {
        $this->session->set_flashdata('error', 'Mapping kelas belum diisi');
        redirect('siswa/kenaikan_kelas');
        return;
    }

    $this->db->trans_start();

    /* ========== 1. LULUSKAN KELAS XII ========== */
    $this->db->query("
        UPDATE siswa s
        JOIN kelas k ON k.id_kelas = s.id_kelas
        SET s.status = 'lulus'
        WHERE k.nama_kelas LIKE 'XII %'
        AND s.status = 'aktif'
    ");

    /* ========== 2. X -> XI ========== */
    if ($map_x) {
        foreach ($map_x as $kelas_lama => $kelas_baru) {
            if (!$kelas_baru) continue;

            $this->db->where('id_kelas', $kelas_lama);
            $this->db->where('status', 'aktif');
            $this->db->update('siswa', [
                'id_kelas' => $kelas_baru
            ]);
        }
    }

    /* ========== 3. XI -> XII ========== */
    if ($map_xi) {
        foreach ($map_xi as $kelas_lama => $kelas_baru) {
            if (!$kelas_baru) continue;

            $this->db->where('id_kelas', $kelas_lama);
            $this->db->where('status', 'aktif');
            $this->db->update('siswa', [
                'id_kelas' => $kelas_baru
            ]);
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Kenaikan kelas gagal');
    } else {
        $this->session->set_flashdata('success', 'Kenaikan kelas berhasil diproses');
    }

    redirect('siswa/kenaikan_kelas');
}
/* =========================================================
 * SIMPAN KENAIKAN KELAS (PER SISWA)
 * ========================================================= */
public function simpan_kenaikan()
{
    $this->only_role([1]);

    $naik = $this->input->post('naik');

    if (!$naik || !is_array($naik)) {
        $this->session->set_flashdata('error', 'Tidak ada data yang diproses');
        redirect($_SERVER['HTTP_REFERER']);
        return;
    }

    $this->db->trans_start();

    foreach ($naik as $id_siswa => $id_kelas_baru) {

        // skip jika tidak dipilih
        if (!$id_kelas_baru) continue;

        $this->db->where('id_siswa', $id_siswa);
        $this->db->where('status', 'aktif');
        $this->db->update('siswa', [
            'id_kelas' => $id_kelas_baru
        ]);
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Gagal menyimpan kenaikan kelas');
    } else {
        $this->session->set_flashdata('success', 'Kenaikan kelas berhasil disimpan');
    }

    redirect($_SERVER['HTTP_REFERER']);
}

}
