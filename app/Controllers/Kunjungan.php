<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Kunjungan extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1,2]); // ADMIN & PETUGAS
        $this->load->model('Kunjungan_model');
    }

    public function index()
    {
        $data = [
            'title'      => 'Daftar Kunjungan Perpustakaan',
            'kunjungan'  => $this->Kunjungan_model->hari_ini()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('kunjungan/index', $data);
        $this->load->view('templates/footer');
    }

    // dipanggil oleh JS scan QR
    public function scan()
{
    $nis = trim($this->input->post('token', true));

    if ($nis === '') {
        echo json_encode(['status' => false, 'msg' => 'QR kosong']);
        return;
    }

    $siswa = $this->db
        ->select('
            siswa.id_siswa,
            siswa.nis,
            siswa.nama_siswa,
            kelas.nama_kelas,
            jurusan.nama_jurusan
        ')
        ->join('kelas','kelas.id_kelas=siswa.id_kelas')
        ->join('jurusan','jurusan.id_jurusan=siswa.id_jurusan')
        ->where('siswa.nis', $nis)
        ->get('siswa')
        ->row();

    if (!$siswa) {
        echo json_encode(['status' => false, 'msg' => 'QR tidak dikenali']);
        return;
    }

    echo json_encode([
        'status' => true,
        'siswa'  => $siswa,
        'jam'    => date('H:i:s')
    ]);
}

// dipanggil oleh scan RFID
public function scan_rfid()
{
    $uid = $this->input->post('rfid_uid', true);

    if (!$uid) {
        echo json_encode([
            'status' => false,
            'msg'    => 'RFID kosong'
        ]);
        return;
    }

    $siswa = $this->db
        ->select('
            siswa.id_siswa,
            siswa.nis,
            siswa.nama_siswa,
            kelas.nama_kelas,
            jurusan.nama_jurusan
        ')
        ->join('kelas','kelas.id_kelas=siswa.id_kelas')
        ->join('jurusan','jurusan.id_jurusan=siswa.id_jurusan')
        ->where('siswa.rfid_uid', $uid)
        ->get('siswa')
        ->row();

    if (!$siswa) {
        echo json_encode([
            'status' => false,
            'msg'    => 'RFID tidak terdaftar'
        ]);
        return;
    }

    echo json_encode([
        'status' => true,
        'siswa'  => $siswa,
        'jam'    => date('H:i:s')
    ]);
}

public function simpan()
{
    $data = [
        'id_siswa' => $this->input->post('id_siswa'),
        'tanggal'  => date('Y-m-d'),
        'jam'      => $this->input->post('jam'),
        'tujuan'   => $this->input->post('tujuan')
    ];

    // anti dobel
    $cek = $this->db->get_where('kunjungan', [
        'id_siswa' => $data['id_siswa'],
        'tanggal'  => $data['tanggal']
    ])->row();

    if ($cek) {
        echo json_encode(['status'=>false,'msg'=>'Siswa sudah tercatat hari ini']);
        return;
    }

    $this->db->insert('kunjungan', $data);

    echo json_encode(['status'=>true,'msg'=>'Kunjungan berhasil disimpan']);
}

}
