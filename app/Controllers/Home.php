<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Home extends CI_Controller {

    public function index()
    {
        $this->load->model('Ebook_model');
        $this->load->model('Buku_fisik_model');

        // LIMIT tampil di home (etalase)
        $data['ebooks'] = $this->Ebook_model
            ->get_filtered(8, 0); // 6 e-book terbaru

        $data['buku_fisik'] = $this->db
            ->limit(6)
            ->order_by('id_buku', 'DESC')
            ->get('buku_fisik')
            ->result();

        $this->load->view('home/index', $data);
    }
}
