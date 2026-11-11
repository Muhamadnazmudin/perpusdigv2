<?php

namespace App\Models;

use CI_Model;

class Peminjaman_model extends CI_Model {

    /* ================= ADMIN ================= */

    public function get_all()
{
    return $this->db
        ->select('
            peminjaman.*,
            buku_fisik.judul,
            users.nama,
            siswa.no_hp,
            CASE
                WHEN peminjaman.status = "dipinjam"
                 AND CURDATE() > peminjaman.tanggal_jatuh_tempo
                THEN DATEDIFF(CURDATE(), peminjaman.tanggal_jatuh_tempo)
                ELSE 0
            END AS hari_terlambat
        ')
        ->from('peminjaman')
        ->join('buku_fisik', 'buku_fisik.id_buku = peminjaman.id_buku')
        ->join('users', 'users.id_user = peminjaman.id_user')
        ->join('siswa', 'siswa.nis = users.username', 'left')
        ->order_by('peminjaman.id_pinjam', 'DESC')
        ->get()
        ->result();
}


    public function get_by_id($id)
    {
        return $this->db
            ->get_where('peminjaman',['id_pinjam'=>$id])
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert('peminjaman', $data);
    }

    public function update($id, $data)
    {
        return $this->db
            ->where('id_pinjam',$id)
            ->update('peminjaman',$data);
    }

    /* ================= SISWA ================= */

    // Cek apakah siswa masih meminjam buku ini
    public function cek_pinjam_aktif($id_user, $id_buku)
    {
        return $this->db
            ->get_where('peminjaman',[
                'id_user' => $id_user,
                'id_buku' => $id_buku,
                'status'  => 'dipinjam'
            ])
            ->row();
    }

    // Daftar pinjaman AKTIF siswa
    public function get_aktif_by_user($id_user)
    {
        return $this->db
            ->select('
    peminjaman.*,
    buku_fisik.judul,
    DATEDIFF(CURDATE(), peminjaman.tanggal_jatuh_tempo) AS hari_terlambat
')

            ->from('peminjaman')
            ->join('buku_fisik','buku_fisik.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_user',$id_user)
            ->where('peminjaman.status','dipinjam')
            ->order_by('peminjaman.id_pinjam','DESC')
            ->get()
            ->result();
    }

    // Riwayat pinjaman siswa
    public function get_riwayat_by_user($id_user)
    {
        return $this->db
            ->select('
    peminjaman.*,
    buku_fisik.judul,
    DATEDIFF(CURDATE(), peminjaman.tanggal_jatuh_tempo) AS hari_terlambat
')
            ->from('peminjaman')
            ->join('buku_fisik','buku_fisik.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_user',$id_user)
            ->order_by('peminjaman.id_pinjam','DESC')
            ->get()
            ->result();
    }
    public function count_pinjam_aktif($id_user)
{
    return $this->db
        ->where('id_user', $id_user)
        ->where('status', 'dipinjam')
        ->count_all_results('peminjaman');
}
public function cek_pinjam_menunggu_atau_aktif($id_user, $id_buku)
{
    return $this->db
        ->where('id_user', $id_user)
        ->where('id_buku', $id_buku)
        ->where_in('status', ['menunggu','dipinjam'])
        ->get('peminjaman')
        ->row();
}
public function get_by_user($id_user)
{
    return $this->db
        ->select('
            peminjaman.*,
            buku_fisik.judul,
            DATEDIFF(
                IFNULL(peminjaman.tanggal_kembali, CURDATE()),
                peminjaman.tanggal_jatuh_tempo
            ) AS hari_terlambat
        ')
        ->from('peminjaman')
        ->join('buku_fisik','buku_fisik.id_buku = peminjaman.id_buku')
        ->where('peminjaman.id_user', $id_user)
        ->order_by('peminjaman.id_pinjam','DESC') // ✅ FIX UTAMA
        ->get()
        ->result();
}

/* ================= LAPORAN ================= */

// LAPORAN PEMINJAMAN
public function get_laporan($awal = null, $akhir = null)
{
    $this->db
        ->select('
            peminjaman.*,
            buku_fisik.judul,
            users.nama,
            siswa.nis,
            kelas.nama_kelas,
            DATEDIFF(
                IFNULL(peminjaman.tanggal_kembali, CURDATE()),
                peminjaman.tanggal_jatuh_tempo
            ) AS hari_terlambat
        ')
        ->from('peminjaman')
        ->join('buku_fisik', 'buku_fisik.id_buku = peminjaman.id_buku', 'left')
        ->join('users', 'users.id_user = peminjaman.id_user', 'left')
        ->join('siswa', 'siswa.nis = users.username', 'left')
        ->join('kelas', 'kelas.id_kelas = siswa.id_kelas', 'left');

    if ($awal && $akhir) {
        $this->db->where('DATE(peminjaman.tanggal_pinjam) >=', $awal);
        $this->db->where('DATE(peminjaman.tanggal_pinjam) <=', $akhir);
    }

    return $this->db
        ->order_by('peminjaman.tanggal_pinjam', 'DESC')
        ->get()
        ->result();
}
public function count_menunggu()
{
    return $this->db
        ->where('status', 'menunggu')
        ->count_all_results('peminjaman');
}
public function get_pengajuan_buku($id_user, $id_buku)
{
    return $this->db
        ->where('id_user', $id_user)
        ->where('id_buku', $id_buku)
        ->where_in('status', ['menunggu','dipinjam','ditolak'])
        ->order_by('id_pinjam', 'DESC')
        ->get('peminjaman')
        ->row();
}
public function get_filtered($limit, $offset, $kelas = null, $keyword = null)
{
    $this->db->select('
        peminjaman.*,
        buku_fisik.judul,
        users.nama,
        siswa.no_hp,
        kelas.nama_kelas,
        CASE
            WHEN peminjaman.status = "dipinjam"
             AND CURDATE() > peminjaman.tanggal_jatuh_tempo
            THEN DATEDIFF(CURDATE(), peminjaman.tanggal_jatuh_tempo)
            ELSE 0
        END AS hari_terlambat
    ');
    $this->db->from('peminjaman');
    $this->db->join('buku_fisik', 'buku_fisik.id_buku = peminjaman.id_buku');
    $this->db->join('users', 'users.id_user = peminjaman.id_user');
    $this->db->join('siswa', 'siswa.nis = users.username', 'left');
    $this->db->join('kelas', 'kelas.id_kelas = siswa.id_kelas', 'left');

    if (!empty($kelas)) {
        $this->db->where('kelas.id_kelas', $kelas);
    }

    if (!empty($keyword)) {
        $this->db->like('users.nama', $keyword);
    }

    $this->db->order_by('peminjaman.id_pinjam', 'DESC');
    $this->db->limit($limit, $offset);

    return $this->db->get()->result();
}
public function count_filtered($kelas = null, $keyword = null)
{
    $this->db->from('peminjaman');
    $this->db->join('users', 'users.id_user = peminjaman.id_user');
    $this->db->join('siswa', 'siswa.nis = users.username', 'left');
    $this->db->join('kelas', 'kelas.id_kelas = siswa.id_kelas', 'left');

    if (!empty($kelas)) {
        $this->db->where('kelas.id_kelas', $kelas);
    }

    if (!empty($keyword)) {
        $this->db->like('users.nama', $keyword);
    }

    return $this->db->count_all_results();
}
public function count_laporan_peminjaman($awal = null, $akhir = null)
{
    $this->db
        ->from('peminjaman')
        ->join('users', 'users.id_user = peminjaman.id_user', 'left')
        ->join('siswa', 'siswa.nis = users.username', 'left')
        ->join('kelas', 'kelas.id_kelas = siswa.id_kelas', 'left');

    if ($awal && $akhir) {
        $this->db->where('DATE(peminjaman.tanggal_pinjam) >=', $awal);
        $this->db->where('DATE(peminjaman.tanggal_pinjam) <=', $akhir);
    }

    return $this->db->count_all_results();
}
public function get_laporan_peminjaman_paginated($limit, $offset, $awal = null, $akhir = null)
{
    $this->db
        ->select('
            peminjaman.*,
            buku_fisik.judul,
            users.nama,
            siswa.nis,
            kelas.nama_kelas,
            DATEDIFF(
                IFNULL(peminjaman.tanggal_kembali, CURDATE()),
                peminjaman.tanggal_jatuh_tempo
            ) AS hari_terlambat
        ')
        ->from('peminjaman')
        ->join('buku_fisik', 'buku_fisik.id_buku = peminjaman.id_buku', 'left')
        ->join('users', 'users.id_user = peminjaman.id_user', 'left')
        ->join('siswa', 'siswa.nis = users.username', 'left')
        ->join('kelas', 'kelas.id_kelas = siswa.id_kelas', 'left');

    if ($awal && $akhir) {
        $this->db->where('DATE(peminjaman.tanggal_pinjam) >=', $awal);
        $this->db->where('DATE(peminjaman.tanggal_pinjam) <=', $akhir);
    }

    return $this->db
        ->order_by('peminjaman.tanggal_pinjam', 'DESC')
        ->limit($limit, $offset)
        ->get()
        ->result();
}


}

if (!class_exists('Peminjaman_model', false)) {
    class_alias('App\\Models\\Peminjaman_model', 'Peminjaman_model');
}
