<?php

namespace App\Models;

use CI_Model;

class Dashboard_model extends CI_Model {

    public function get_statistik()
    {
        return [
            'total_buku'   => $this->db->count_all('buku_fisik'),
            'total_ebook'  => $this->db->count_all('ebook'),
            'total_user'   => $this->db->count_all('users'),
            'total_pinjam' => $this->db->count_all('peminjaman')
        ];
    }
    public function get_stat_admin()
{
    return [
        'total_buku_fisik'   => $this->db->count_all('buku_fisik'),
        'total_buku_digital' => $this->db->count_all('ebook'),

        // ⬇️ INI YANG FIX
        'total_siswa' => $this->db
            ->where('id_role', 3)
            ->count_all_results('users'),

        'total_guru' => $this->db
            ->where('id_role', 2)
            ->count_all_results('users'),

        'total_rak'      => $this->db->count_all('rak'),
        'total_kategori' => $this->db->count_all('kategori'),
    ];
}

public function get_stat_guru($id_guru)
{
    return [
        'dipinjam' => $this->db
            ->where('id_guru', $id_guru)
            ->where('status', 'dipinjam')
            ->count_all_results('peminjaman'),

        'riwayat' => $this->db
            ->where('id_guru', $id_guru)
            ->count_all_results('peminjaman'),
    ];
}

public function get_stat_siswa($id_user)
{
    return [
        'dipinjam' => $this->db
            ->where('id_user', $id_user)
            ->where('status', 'dipinjam')
            ->count_all_results('peminjaman'),

        'riwayat' => $this->db
            ->where('id_user', $id_user)
            ->count_all_results('peminjaman'),
    ];
}
public function get_stat_user($id_user)
{
    return [

        'dipinjam' => $this->db
            ->where('id_user', $id_user)
            ->where('status', 'dipinjam')
            ->count_all_results('peminjaman'),

        'terlambat' => $this->db
            ->where('id_user', $id_user)
            ->where('status', 'terlambat')
            ->count_all_results('peminjaman'),

        'riwayat' => $this->db
            ->where('id_user', $id_user)
            ->count_all_results('peminjaman'),
    ];
}
}

if (!class_exists('Dashboard_model', false)) {
    class_alias('App\\Models\\Dashboard_model', 'Dashboard_model');
}
