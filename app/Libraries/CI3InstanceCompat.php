<?php

namespace App\Libraries;

class CI3InstanceCompat
{
    public CI3SessionCompat $session;
    public CI3InputCompat $input;
    public CI3DBCompat $db;
    public array $user = [];
    public array $data = [];

    public function __construct()
    {
        $this->session = new CI3SessionCompat();
        $this->input   = new CI3InputCompat();
        $this->db      = new CI3DBCompat();
        $this->user    = $this->session->userdata('user') ?? [];
    }

    public function __get(string $name)
    {
        if ($name === 'session') return $this->session;
        if ($name === 'input') return $this->input;
        if ($name === 'db') return $this->db;
        if ($name === 'user') return $this->user;
        if ($name === 'data') return $this->data;
        return null;
    }
}

