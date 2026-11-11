<?php

namespace App\Models;

use CI_Model;

class Kategori_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->order_by('nama_kategori', 'ASC')
            ->get('kategori')
            ->result();
    }

    // ✅ DIPAKAI DI LIST (ADA HITUNG BUKU)
    public function get_with_count()
    {
        return $this->db
            ->select('kategori.*, COUNT(buku_fisik.id_buku) AS total_buku')
            ->from('kategori')
            ->join('buku_fisik', 'buku_fisik.id_kategori = kategori.id_kategori', 'left')
            ->group_by('kategori.id_kategori')
            ->order_by('kategori.nama_kategori', 'ASC')
            ->get()
            ->result();
    }

    // ✅ TAMBAH
    public function insert($data)
    {
        return $this->db->insert('kategori', $data);
    }

    // ✅ INI YANG ERROR KEMARIN
    public function update($id, $data)
    {
        return $this->db
            ->where('id_kategori', $id)
            ->update('kategori', $data);
    }

    // ✅ HAPUS
    public function delete($id)
    {
        return $this->db
            ->where('id_kategori', $id)
            ->delete('kategori');
    }

    // ✅ PROTEKSI HAPUS
    public function is_used($id)
    {
        return $this->db
            ->where('id_kategori', $id)
            ->count_all_results('buku_fisik') > 0;
    }
}

if (!class_exists('Kategori_model', false)) {
    class_alias('App\\Models\\Kategori_model', 'Kategori_model');
}
