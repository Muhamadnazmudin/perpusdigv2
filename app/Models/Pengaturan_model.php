<?php

namespace App\Models;

use CI_Model;

class Pengaturan_model extends CI_Model {

    private $table = 'pengaturan_sekolah';

    public function get()
    {
        return $this->db->get($this->table)->row();
    }

    public function simpan()
    {
        $data = [
            'nama_sekolah' => $this->input->post('nama_sekolah', true),
            'npsn' => $this->input->post('npsn', true),
            'alamat_sekolah' => $this->input->post('alamat_sekolah', true),
            'email_sekolah' => $this->input->post('email_sekolah', true),
            'telp_sekolah' => $this->input->post('telp_sekolah', true),
            'website_sekolah' => $this->input->post('website_sekolah', true),

            'kepala_sekolah' => $this->input->post('kepala_sekolah', true),
            'nip_kepala_sekolah' => $this->input->post('nip_kepala_sekolah', true),

            'kepala_perpustakaan' => $this->input->post('kepala_perpustakaan', true),
            'nip_kepala_perpustakaan' => $this->input->post('nip_kepala_perpustakaan', true),

            'petugas_perpustakaan' => $this->input->post('petugas_perpustakaan', true),
            'nip_petugas_perpustakaan' => $this->input->post('nip_petugas_perpustakaan', true),
        ];

        $cek = $this->db->count_all($this->table);

        if ($cek == 0) {
            $this->db->insert($this->table, $data);
        } else {
            $this->db->update($this->table, $data, ['id' => 1]);
        }
    }
}

if (!class_exists('Pengaturan_model', false)) {
    class_alias('App\\Models\\Pengaturan_model', 'Pengaturan_model');
}
