<?php

namespace App\Models;

use CI_Model;

class User_model extends CI_Model {

   public function get_by_username($username)
{
    return $this->db
        ->where('username', $username)
        ->where('status', 'aktif')
        ->get('users')
        ->row_array();
}
public function get_siswa()
{
    return $this->db
        ->where('id_role', 3)
        ->where('status', 'aktif')
        ->get('users')
        ->result();
}

}

if (!class_exists('User_model', false)) {
    class_alias('App\\Models\\User_model', 'User_model');
}
