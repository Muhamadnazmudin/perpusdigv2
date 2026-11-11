<?php

namespace App\Models;

use CI_Model;

class Ebook_model extends CI_Model {

    private $table = 'ebook';

    public function get_all()
    {
        return $this->db
            ->order_by('id_ebook','DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where($this->table,['id_ebook'=>$id])
            ->row();
    }

    public function count_filtered($keyword=null, $kelas=null, $mapel=null)
{
    // 🔐 HANYA EBOOK PUBLIK
    $this->db->where('status', 'APPROVED');
    $this->db->where('is_public', 1);

    if ($keyword) $this->db->like('judul', $keyword);
    if ($kelas)   $this->db->where('kelas', $kelas);
    if ($mapel)   $this->db->where('mapel', $mapel);

    return $this->db->count_all_results($this->table);
}

public function get_filtered($limit, $offset, $keyword=null, $kelas=null, $mapel=null)
{
    $this->db->select('ebook.*, users.nama AS penulis');
    $this->db->from($this->table);
    $this->db->join('users', 'users.id_user = ebook.created_by', 'left');

    // 🔐 hanya ebook publik
    $this->db->where('ebook.status', 'APPROVED');
    $this->db->where('ebook.is_public', 1);

    if ($keyword) $this->db->like('ebook.judul', $keyword);
    if ($kelas)   $this->db->where('ebook.kelas', $kelas);
    if ($mapel)   $this->db->where('ebook.mapel', $mapel);

    return $this->db
        ->order_by('ebook.id_ebook', 'ASC')
        ->limit($limit, $offset)
        ->get()
        ->result();
}

    public function delete($id)
    {
        return $this->db
            ->where('id_ebook',$id)
            ->delete($this->table);
    }

    public function get_mapel_unik()
    {
        return $this->db
            ->select('mapel')
            ->distinct()
            ->get($this->table)
            ->result();
    }
    public function get_karya_siswa()
{
    return $this->db
        ->select('ebook.*, users.nama AS penulis')
        ->from('ebook')
        ->join('users', 'users.id_user = ebook.created_by', 'left')
        ->where('ebook.created_by IS NOT NULL')
        ->order_by('ebook.created_at', 'DESC')
        ->get()
        ->result();
}
/* ================= ADMIN ================= */
public function count_all_admin($keyword=null, $kelas=null, $mapel=null)
{
    if ($keyword) $this->db->like('judul', $keyword);
    if ($kelas)   $this->db->where('kelas', $kelas);
    if ($mapel)   $this->db->where('mapel', $mapel);

    return $this->db->count_all_results($this->table);
}

public function get_all_admin($limit, $offset, $keyword=null, $kelas=null, $mapel=null)
{
    $this->db->select('ebook.*, users.nama AS penulis');
    $this->db->from($this->table);
    $this->db->join('users', 'users.id_user = ebook.created_by', 'left');

    if ($keyword) $this->db->like('ebook.judul', $keyword);
    if ($kelas)   $this->db->where('ebook.kelas', $kelas);
    if ($mapel)   $this->db->where('ebook.mapel', $mapel);

    return $this->db
        ->order_by('ebook.id_ebook', 'ASC')
        ->limit($limit, $offset)
        ->get()
        ->result();
}

}

if (!class_exists('Ebook_model', false)) {
    class_alias('App\\Models\\Ebook_model', 'Ebook_model');
}
