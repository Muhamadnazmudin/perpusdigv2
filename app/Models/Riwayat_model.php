<?php

namespace App\Models;

use CI_Model;

class Riwayat_model extends CI_Model {

    public function save_or_update($id_user, $id_ebook)
    {
        $cek = $this->db->get_where('riwayat_baca', [
            'id_user'  => $id_user,
            'id_ebook' => $id_ebook
        ])->row();

        if ($cek) {
            $this->db->where('id_riwayat', $cek->id_riwayat)
                     ->update('riwayat_baca', [
                         'last_read' => date('Y-m-d H:i:s')
                     ]);
        } else {
            $this->db->insert('riwayat_baca', [
                'id_user'  => $id_user,
                'id_ebook' => $id_ebook
            ]);
        }
    }

    public function get_by_user($id_user)
    {
        return $this->db
            ->select('ebook.*, riwayat_baca.last_read')
            ->from('riwayat_baca')
            ->join('ebook','ebook.id_ebook = riwayat_baca.id_ebook')
            ->where('riwayat_baca.id_user', $id_user)
            ->order_by('riwayat_baca.last_read','DESC')
            ->get()
            ->result();
    }
    public function update_progress($id_user, $id_ebook, $page)
{
    $this->db->where([
        'id_user' => $id_user,
        'id_ebook' => $id_ebook
    ])->update('riwayat_baca', [
        'progress' => $page,
        'last_read' => date('Y-m-d H:i:s')
    ]);
}

}

if (!class_exists('Riwayat_model', false)) {
    class_alias('App\\Models\\Riwayat_model', 'Riwayat_model');
}
