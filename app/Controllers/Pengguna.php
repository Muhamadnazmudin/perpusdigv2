<?php

namespace App\Controllers;

use CI_Controller;
use MY_Controller;
use ZipArchive;

class Pengguna extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        // 🔐 HANYA ADMIN
        if ($this->user['id_role'] !== 1) {
            show_error('Akses ditolak', 403);
        }

        $this->load->model('Pengguna_model');
    }

    public function index()
{
    $this->load->library('pagination');

    $limit = 10;
    $start = (int) $this->uri->segment(3);

    $config['base_url'] = site_url('pengguna/index');
    $config['total_rows'] = $this->Pengguna_model->count_all();
    $config['per_page'] = $limit;
    $config['uri_segment'] = 3;

    // bootstrap pagination
    $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
    $config['full_tag_close'] = '</ul>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';
    $config['attributes'] = ['class' => 'page-link'];

    $this->pagination->initialize($config);

    $data['title'] = 'User Management';
    $data['users'] = $this->Pengguna_model->get_limit($limit, $start);
    $data['pagination'] = $this->pagination->create_links();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar',  $this->data);
    $this->load->view('pengguna/index', $data);
    $this->load->view('templates/footer');
}


    public function role($role)
    {
        // validasi role
        if (!in_array((int)$role, [1,2,3], true)) {
            show_error('Role tidak valid', 404);
        }

        $data['title'] = 'User Management';
        $data['users'] = $this->Pengguna_model->get_by_role($role);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $this->data);
        $this->load->view('templates/topbar',  $this->data);
        $this->load->view('pengguna/index', $data);
        $this->load->view('templates/footer');
    }
    public function edit($id)
{
    $data['user'] = $this->Pengguna_model->get_by_id($id);

    if (!$data['user']) {
        show_404();
    }

    if ($this->input->post()) {

        $update = [
            'username' => $this->input->post('username'),
            'status'   => $this->input->post('status')
        ];

        // ganti password jika diisi
        if ($this->input->post('password')) {
            $update['password'] = password_hash(
                $this->input->post('password'),
                PASSWORD_DEFAULT
            );
        }

        $this->Pengguna_model->update($id, $update);
        redirect('pengguna');
    }

    $data['title'] = 'Edit User';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar',  $this->data);
    $this->load->view('pengguna/edit', $data);
    $this->load->view('templates/footer');
}
public function tambah_admin()
{
    if ($this->input->post()) {

        $data = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'id_role'  => 1,
            'status'   => 'aktif'
        ];

        $this->db->insert('users', $data);
        redirect('pengguna');
    }

    $data['title'] = 'Tambah Admin';

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $this->data);
    $this->load->view('templates/topbar',  $this->data);
    $this->load->view('pengguna/tambah_admin');
    $this->load->view('templates/footer');
}

}
