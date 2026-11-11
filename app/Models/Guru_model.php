<?php

namespace App\Models;

use CI_Model;

class Guru_model extends CI_Model {

    public function get_all() {
        return $this->db->get('guru')->result();
    }

    public function insert($data) {
        return $this->db->insert('guru', $data);
    }
}

if (!class_exists('Guru_model', false)) {
    class_alias('App\\Models\\Guru_model', 'Guru_model');
}
