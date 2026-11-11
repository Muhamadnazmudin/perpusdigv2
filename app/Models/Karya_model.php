<?php

namespace App\Models;

use CI_Model;

class Karya_model extends CI_Model {

    private $table = 'ebook';

    /* ===================== SEMUA KARYA SISWA ===================== */
    public function get_all()
    {
        return $this->db
            ->select('ebook.*, users.nama AS penulis')
            ->from($this->table)
            ->join('users', 'users.id_user = ebook.created_by', 'left')
            ->where('ebook.created_by IS NOT NULL')
            ->order_by('ebook.created_at', 'DESC')
            ->get()
            ->result();
    }

    /* ===================== KHUSUS PENDING ===================== */
    public function get_pending()
    {
        return $this->db
            ->select('ebook.*, users.nama AS penulis')
            ->from($this->table)
            ->join('users', 'users.id_user = ebook.created_by', 'left')
            ->where('ebook.created_by IS NOT NULL')
            ->where('ebook.status', 'PENDING')
            ->order_by('ebook.created_at', 'DESC')
            ->get()
            ->result();
    }

    /* ===================== DETAIL ===================== */
    public function get_by_id($id)
    {
        return $this->db
            ->select('ebook.*, users.nama AS penulis')
            ->from($this->table)
            ->join('users', 'users.id_user = ebook.created_by', 'left')
            ->where('ebook.id_ebook', $id)
            ->get()
            ->row();
    }

    /* ===================== UPDATE STATUS ===================== */
    public function update_status($id, $data)
    {
        return $this->db
            ->where('id_ebook', $id)
            ->update($this->table, $data);
    }
    /* ===================== KARYA MILIK SISWA ===================== */
public function get_by_user($id_user)
{
    return $this->db
        ->select('ebook.*')
        ->from('ebook')
        ->where('created_by', $id_user)
        ->order_by('created_at', 'DESC')
        ->get()
        ->result();
}

}

if (!class_exists('Karya_model', false)) {
    class_alias('App\\Models\\Karya_model', 'Karya_model');
}
