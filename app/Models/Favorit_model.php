<?php

namespace App\Models;

use CI_Model;

class Favorit_model extends CI_Model {

    public function toggle($id_user, $id_ebook)
    {
        $cek = $this->db->get_where('ebook_favorit', [
            'id_user' => $id_user,
            'id_ebook' => $id_ebook
        ])->row();

        if ($cek) {
            $this->db->where('id_favorit', $cek->id_favorit)->delete('ebook_favorit');
            return false;
        } else {
            $this->db->insert('ebook_favorit', [
                'id_user' => $id_user,
                'id_ebook' => $id_ebook
            ]);
            return true;
        }
    }

    public function is_favorit($id_user, $id_ebook)
    {
        return $this->db->get_where('ebook_favorit', [
            'id_user' => $id_user,
            'id_ebook' => $id_ebook
        ])->num_rows() > 0;
    }

    public function get_by_user($id_user)
    {
        return $this->db
            ->select('ebook.*')
            ->from('ebook_favorit')
            ->join('ebook','ebook.id_ebook = ebook_favorit.id_ebook')
            ->where('ebook_favorit.id_user',$id_user)
            ->get()->result();
    }
}

if (!class_exists('Favorit_model', false)) {
    class_alias('App\\Models\\Favorit_model', 'Favorit_model');
}
