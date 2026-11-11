<?php

namespace App\Models;

use CI_Model;

class Siswa_model extends CI_Model {

    public function get_all() {
    $this->db->select('siswa.*, kelas.nama_kelas, jurusan.nama_jurusan');
    $this->db->join('kelas', 'kelas.id_kelas = siswa.id_kelas');
    $this->db->join('jurusan', 'jurusan.id_jurusan = siswa.id_jurusan');
    return $this->db->get('siswa')->result();
}

    public function insert($data) {
        return $this->db->insert('siswa', $data);
    }
    public function count_all()
{
    return $this->db->count_all('siswa');
}

public function get_limit($limit, $offset)
{
    $this->db->select('siswa.*, kelas.nama_kelas, jurusan.nama_jurusan');
    $this->db->join('kelas','kelas.id_kelas=siswa.id_kelas');
    $this->db->join('jurusan','jurusan.id_jurusan=siswa.id_jurusan');
    $this->db->limit($limit, $offset);
    return $this->db->get('siswa')->result();
}
public function get_by_nis($nis)
{
    $this->db->select('
        siswa.*,
        kelas.nama_kelas,
        jurusan.nama_jurusan
    ');
    $this->db->join('kelas','kelas.id_kelas = siswa.id_kelas');
    $this->db->join('jurusan','jurusan.id_jurusan = siswa.id_jurusan');
    return $this->db->get_where('siswa', ['nis' => $nis])->row();
}
public function get_paginated($limit, $offset)
{
    return $this->db
        ->select('
            siswa.*,
            kelas.nama_kelas,
            jurusan.nama_jurusan
        ')
        ->from('siswa')
        ->join('kelas', 'kelas.id_kelas = siswa.id_kelas')
        ->join('jurusan', 'jurusan.id_jurusan = siswa.id_jurusan')
        ->order_by('siswa.nama_siswa', 'ASC')
        ->limit($limit, $offset)
        ->get()
        ->result();
}

public function get_kelas()
{
    return $this->db->get('kelas')->result();
}

public function get_jurusan()
{
    return $this->db->get('jurusan')->result();
}
private function base_query()
{
    $this->db
        ->select('siswa.*, kelas.nama_kelas, jurusan.nama_jurusan')
        ->from('siswa')
        ->join('kelas', 'kelas.id_kelas = siswa.id_kelas')
        ->join('jurusan', 'jurusan.id_jurusan = siswa.id_jurusan');
}

public function count_filtered($kelas=null, $keyword=null)
{
    $this->base_query();

    if($kelas){
        $this->db->where('siswa.id_kelas', $kelas);
    }

    if($keyword){
        $this->db->group_start()
                 ->like('siswa.nama_siswa', $keyword)
                 ->or_like('siswa.nis', $keyword)
                 ->group_end();
    }

    return $this->db->count_all_results();
}

public function get_filtered($limit, $offset, $kelas=null, $keyword=null)
{
    $this->base_query();

    if($kelas){
        $this->db->where('siswa.id_kelas', $kelas);
    }

    if($keyword){
        $this->db->group_start()
                 ->like('siswa.nama_siswa', $keyword)
                 ->or_like('siswa.nis', $keyword)
                 ->group_end();
    }

    return $this->db
        ->order_by('siswa.nama_siswa','ASC')
        ->limit($limit, $offset)
        ->get()
        ->result();
}

}

if (!class_exists('Siswa_model', false)) {
    class_alias('App\\Models\\Siswa_model', 'Siswa_model');
}
