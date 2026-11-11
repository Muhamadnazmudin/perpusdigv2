<?php

namespace App\Models;

use CI_Model;

class Settings_model extends CI_Model {

    public function get($name)
    {
        return $this->db
            ->get_where('settings', ['name' => $name])
            ->row('value');
    }

    public function set($name, $value)
    {
        return $this->db
            ->where('name', $name)
            ->update('settings', ['value' => $value]);
    }
}

if (!class_exists('Settings_model', false)) {
    class_alias('App\\Models\\Settings_model', 'Settings_model');
}
