<?php

namespace App\Models;

use CI_Model;

class Quran_model extends CI_Model {

    public function get_all_surah()
    {
        return $this->db->order_by('id','ASC')
                        ->get('quran_surah')
                        ->result();
    }

    public function get_surah($id)
    {
        return $this->db->get_where('quran_surah', ['id' => $id])
                        ->row();
    }

    public function get_ayat($surah_id)
    {
        return $this->db->order_by('nomor','ASC')
                        ->get_where('quran_ayat', ['surah_id' => $surah_id])
                        ->result();
    }

    public function set_bookmark($user_id, $surah_id, $ayat_id)
{
    $cek = $this->db->get_where('quran_bookmark', [
        'user_id' => $user_id
    ])->row();

    $data = [
        'user_id'   => $user_id,
        'surah_id'  => $surah_id,
        'ayat_id'   => $ayat_id,
        'created_at'=> date('Y-m-d H:i:s')
    ];

    if ($cek) {
        $this->db->where('user_id', $user_id);
        $this->db->update('quran_bookmark', $data);
    } else {
        $this->db->insert('quran_bookmark', $data);
    }
}

    public function get_last_bookmark($user_id)
{
    return $this->db
        ->select('b.*, s.nama_latin, s.nama')
        ->from('quran_bookmark b')
        ->join('quran_surah s', 's.id = b.surah_id')
        ->where('b.user_id', $user_id)
        ->order_by('b.id', 'DESC')
        ->limit(1)
        ->get()
        ->row();
}
}
if (!class_exists('Quran_model', false)) {
    class_alias('App\\Models\\Quran_model', 'Quran_model');
}
