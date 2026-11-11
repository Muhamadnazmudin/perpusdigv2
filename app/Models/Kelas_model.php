<?php

namespace App\Models;

use CI_Model;

class Kelas_model extends CI_Model {

    public function get_all() {
        return $this->db->get('kelas')->result();
    }

    public function insert($data) {
        return $this->db->insert('kelas', $data);
    }

    public function delete($id) {
        return $this->db->delete('kelas', ['id_kelas' => $id]);
    }
}

if (!class_exists('Kelas_model', false)) {
    class_alias('App\\Models\\Kelas_model', 'Kelas_model');
}
