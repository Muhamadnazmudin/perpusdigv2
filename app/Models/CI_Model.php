<?php

#[\AllowDynamicProperties]
class CI_Model
{
    public \App\Libraries\CI3DBCompat $db;
    public \App\Libraries\CI3InputCompat $input;
    public \App\Libraries\CI3SessionCompat $session;

    public function __construct()
    {
        $this->db      = new \App\Libraries\CI3DBCompat();
        $this->input   = new \App\Libraries\CI3InputCompat();
        $this->session = new \App\Libraries\CI3SessionCompat();
    }

    public function __get(string $name)
    {
        if ($name === 'db') return $this->db;
        if ($name === 'input') return $this->input;
        if ($name === 'session') return $this->session;
        return null;
    }
}

