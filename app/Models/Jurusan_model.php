<?php

namespace App\Models;

use CI_Model;

class Jurusan_model extends CI_Model {

    public function get_all() {
        return $this->db->get('jurusan')->result();
    }

    public function insert($data) {
        return $this->db->insert('jurusan', $data);
    }

    public function delete($id) {
        return $this->db->delete('jurusan', ['id_jurusan' => $id]);
    }
}

if (!class_exists('Jurusan_model', false)) {
    class_alias('App\\Models\\Jurusan_model', 'Jurusan_model');
}
