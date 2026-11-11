<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Kategori extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->only_role([1]);
        $this->load->model('Kategori_model');
    }

    public function index()
    {
        $data['title'] = 'Kategori Buku';
        $data['kategori'] = $this->Kategori_model->get_with_count();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('kategori/index', $data);
        $this->load->view('templates/footer');
    }

    public function simpan()
    {
        $this->Kategori_model->insert([
            'nama_kategori' => $this->input->post('nama_kategori', TRUE)
        ]);

        $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan');
        redirect('kategori');
    }

    public function update()
    {
        $id = $this->input->post('id_kategori');

        $this->Kategori_model->update($id, [
            'nama_kategori' => $this->input->post('nama_kategori', TRUE)
        ]);

        $this->session->set_flashdata('success', 'Kategori berhasil diperbarui');
        redirect('kategori');
    }

    public function hapus($id)
    {
        if ($this->Kategori_model->is_used($id)) {
            $this->session->set_flashdata(
                'error',
                'Kategori tidak bisa dihapus karena sedang digunakan oleh buku.'
            );
            redirect('kategori');
            return;
        }

        $this->Kategori_model->delete($id);

        $this->session->set_flashdata('success', 'Kategori berhasil dihapus');
        redirect('kategori');
    }
}
