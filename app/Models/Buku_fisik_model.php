<?php

namespace App\Models;

use CI_Model;

class Buku_fisik_model extends CI_Model {

    /* ================= LIST ================= */

    public function get_all()
    {
        return $this->db
            ->select('
                buku_fisik.*,
                kategori.nama_kategori,
                rak.kode_rak
            ')
            ->from('buku_fisik')
            ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
            ->join('rak', 'rak.id_rak = buku_fisik.id_rak', 'left')
            ->order_by('buku_fisik.judul', 'ASC')
            ->get()
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where('buku_fisik', ['id_buku' => $id])
            ->row();
    }
/* ================= PAGINATION + SEARCH ================= */

public function get_filtered($limit, $offset, $keyword = null)
{
    $this->db
        ->select('
            buku_fisik.*,
            kategori.nama_kategori,
            rak.kode_rak
        ')
        ->from('buku_fisik')
        ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
        ->join('rak', 'rak.id_rak = buku_fisik.id_rak', 'left');

    if (!empty($keyword)) {
        $this->db->like('buku_fisik.judul', $keyword);
    }

    $this->db->order_by('buku_fisik.judul', 'ASC');
    $this->db->limit($limit, $offset);

    return $this->db->get()->result();
}

public function count_filtered($keyword = null)
{
    $this->db->from('buku_fisik');

    if (!empty($keyword)) {
        $this->db->like('judul', $keyword);
    }

    return $this->db->count_all_results();
}

    /* ================= INSERT ================= */

    public function insert($data)
    {
        return $this->db->insert('buku_fisik', $data);
    }

    // ✅ UNTUK IMPORT EXCEL
    public function insert_batch($data)
    {
        return $this->db->insert_batch('buku_fisik', $data);
    }

    /* ================= UPDATE ================= */

    public function update($id, $data)
    {
        return $this->db
            ->where('id_buku', $id)
            ->update('buku_fisik', $data);
    }

    /* ================= DELETE ================= */

    public function delete($id)
    {
        return $this->db
            ->where('id_buku', $id)
            ->delete('buku_fisik');
    }

    /* ================= DETAIL ================= */

    public function get_detail($id)
    {
        return $this->db
            ->select('
                buku_fisik.*,
                kategori.nama_kategori,
                rak.kode_rak,
                rak.lokasi
            ')
            ->from('buku_fisik')
            ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
            ->join('rak', 'rak.id_rak = buku_fisik.id_rak', 'left')
            ->where('buku_fisik.id_buku', $id)
            ->get()
            ->row();
    }

    /* ================= REKAP ================= */

    // 🔧 FIX: sebelumnya pakai kolom "kategori" (tidak ada)
    public function get_rekap()
{
    return $this->db
        ->select('
            bf.judul,
            bf.isbn,
            bf.penulis,
            bf.penerbit,
            bf.tahun,

            SUM(bf.stok) AS stok_awal,

            COALESCE(SUM(
                CASE 
                    WHEN p.status = "dipinjam" THEN 1 
                    ELSE 0 
                END
            ), 0) AS dipinjam
        ')
        ->from('buku_fisik bf')
        ->join('peminjaman p', 'p.id_buku = bf.id_buku', 'left')
        ->group_by([
            'bf.judul',
            'bf.isbn',
            'bf.penulis',
            'bf.penerbit',
            'bf.tahun'
        ])
        ->order_by('bf.judul', 'ASC')
        ->get()
        ->result();
}


public function cek_isbn($isbn)
{
    return $this->db
        ->where('isbn', $isbn)
        ->get('buku_fisik')
        ->row();
}
public function isbn_exists($isbn, $exclude_id = null)
{
    if (empty($isbn)) {
        return false;
    }

    $this->db->where('isbn', $isbn);

    if ($exclude_id) {
        $this->db->where('id_buku !=', $exclude_id);
    }

    return $this->db->get('buku_fisik')->row();
}
/* ================= LIST KELAS DARI BUKU ================= */

public function get_list_kelas()
{
    return $this->db
        ->select('kelas')
        ->from('buku_fisik')
        ->where('kelas IS NOT NULL')
        ->group_by('kelas')
        ->order_by('kelas', 'ASC')
        ->get()
        ->result();
}
/* ================= FILTER BUKU SISWA ================= */

public function get_filtered_siswa($kelas = null, $keyword = null, $limit = null, $offset = null)
{
    $this->db
        ->select('
            buku_fisik.*,
            kategori.nama_kategori,
            rak.kode_rak
        ')
        ->from('buku_fisik')
        ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
        ->join('rak', 'rak.id_rak = buku_fisik.id_rak', 'left');

    if (!empty($kelas)) {
        $this->db->where('buku_fisik.kelas', $kelas);
    }

    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('buku_fisik.judul', $keyword);
        $this->db->or_like('buku_fisik.penulis', $keyword);
        $this->db->group_end();
    }

    if ($limit !== null) {
        $this->db->limit($limit, $offset);
    }

    return $this->db
        ->order_by('buku_fisik.judul', 'ASC')
        ->get()
        ->result();
}
public function count_filtered_siswa($kelas = null, $keyword = null)
{
    $this->db->from('buku_fisik');

    if (!empty($kelas)) {
        $this->db->where('kelas', $kelas);
    }

    if (!empty($keyword)) {
        $this->db->group_start();
        $this->db->like('judul', $keyword);
        $this->db->or_like('penulis', $keyword);
        $this->db->group_end();
    }

    return $this->db->count_all_results();
}
public function count_rekap()
{
    return $this->db
        ->select('bf.judul')
        ->from('buku_fisik bf')
        ->group_by([
            'bf.judul',
            'bf.isbn',
            'bf.penulis',
            'bf.penerbit',
            'bf.tahun'
        ])
        ->get()
        ->num_rows();
}
public function get_rekap_paginated($limit, $offset)
{
    return $this->db
        ->select('
            bf.judul,
            bf.isbn,
            bf.penulis,
            bf.penerbit,
            bf.tahun,
            SUM(bf.stok) AS stok_awal,

            COALESCE(SUM(
                CASE 
                    WHEN p.status = "dipinjam" THEN 1 
                    ELSE 0 
                END
            ), 0) AS dipinjam
        ')
        ->from('buku_fisik bf')
        ->join('peminjaman p', 'p.id_buku = bf.id_buku', 'left')
        ->group_by([
            'bf.judul',
            'bf.isbn',
            'bf.penulis',
            'bf.penerbit',
            'bf.tahun'
        ])
        ->order_by('bf.judul', 'ASC')
        ->limit($limit, $offset)
        ->get()
        ->result();
}
public function get_ready()
{
    return $this->db
        ->select('
            buku_fisik.*,
            kategori.nama_kategori,
            rak.kode_rak
        ')
        ->from('buku_fisik')
        ->join('kategori', 'kategori.id_kategori = buku_fisik.id_kategori', 'left')
        ->join('rak', 'rak.id_rak = buku_fisik.id_rak', 'left')
        ->where('buku_fisik.stok >', 0)
        ->order_by('buku_fisik.judul', 'ASC')
        ->get()
        ->result();
}

}

if (!class_exists('Buku_fisik_model', false)) {
    class_alias('App\\Models\\Buku_fisik_model', 'Buku_fisik_model');
}
