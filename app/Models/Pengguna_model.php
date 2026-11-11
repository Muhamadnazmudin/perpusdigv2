<?php

namespace App\Models;

use CI_Model;

class Pengguna_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->select('u.*, g.nama_guru, s.nama_siswa')
            ->from('users u')
            ->join('guru g', 'g.nip = u.username AND u.id_role = 2', 'left')
            ->join('siswa s', 's.nis = u.username AND u.id_role = 3', 'left')
            ->order_by('u.id_role', 'ASC')
            ->get()
            ->result();
    }

    public function get_by_role($role)
    {
        return $this->db
            ->select('u.*, g.nama_guru, s.nama_siswa')
            ->from('users u')
            ->join('guru g', 'g.nip = u.username', 'left')
            ->join('siswa s', 's.nis = u.username', 'left')
            ->where('u.id_role', $role)
            ->get()
            ->result();
    }
    public function count_all()
{
    return $this->db->count_all('users');
}

public function get_limit($limit, $start)
{
    return $this->db
        ->select('u.*, g.nama_guru, s.nama_siswa')
        ->from('users u')
        ->join('guru g', 'g.nip = u.username AND u.id_role = 2', 'left')
        ->join('siswa s', 's.nis = u.username AND u.id_role = 3', 'left')
        ->limit($limit, $start)
        ->order_by('u.id_role', 'ASC')
        ->get()
        ->result();
}
public function get_by_id($id)
{
    return $this->db->get_where('users', ['id_user' => $id])->row();
}

public function update($id, $data)
{
    $this->db->where('id_user', $id)->update('users', $data);
}

}

if (!class_exists('Pengguna_model', false)) {
    class_alias('App\\Models\\Pengguna_model', 'Pengguna_model');
}
