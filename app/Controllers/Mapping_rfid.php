<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Mapping_rfid extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]); // ADMIN
        $this->load->model(['Kelas_model']);
    }

    public function index()
    {
        $data = [
            'title' => 'Mapping RFID Siswa',
            'kelas' => $this->Kelas_model->get_all()
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('siswa/mapping_rfid', $data);
        $this->load->view('templates/footer');
    }

    public function get_siswa()
    {
        $id_kelas = $this->input->post('id_kelas');

        $siswa = $this->db
            ->where('id_kelas', $id_kelas)
            ->order_by('nama_siswa','ASC')
            ->get('siswa')
            ->result();

        echo json_encode($siswa);
    }

    public function simpan()
    {
        $id_siswa = $this->input->post('id_siswa');
        $rfid     = trim($this->input->post('rfid_uid'));

        // anti RFID dobel
        if ($this->db->get_where('siswa', ['rfid_uid'=>$rfid])->row()) {
            echo json_encode(['status'=>false,'msg'=>'RFID sudah digunakan']);
            return;
        }

        $this->db->where('id_siswa',$id_siswa)
                 ->update('siswa',['rfid_uid'=>$rfid]);

        echo json_encode(['status'=>true]);
    }

    public function reset_kelas()
    {
        $id_kelas = $this->input->post('id_kelas');

        $this->db->where('id_kelas',$id_kelas)
                 ->update('siswa',['rfid_uid'=>null]);

        echo json_encode(['status'=>true]);
    }
}
