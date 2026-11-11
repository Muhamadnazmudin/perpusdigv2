<?php

namespace App\Models;

use CI_Model;

class Rak_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->order_by('kode_rak', 'ASC')
            ->get('rak')
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert('rak', $data);
    }
}

if (!class_exists('Rak_model', false)) {
    class_alias('App\\Models\\Rak_model', 'Rak_model');
}
