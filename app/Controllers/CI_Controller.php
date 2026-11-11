<?php

require_once __DIR__ . '/BaseController.php';

#[\AllowDynamicProperties]
class CI_Controller extends \App\Controllers\BaseController
{
    public \App\Libraries\CI3LoaderCompat $load;
    public \App\Libraries\CI3SessionCompat $session;
    public \App\Libraries\CI3InputCompat $input;
    public \App\Libraries\CI3DBCompat $db;
    public object $router;
    public $upload;
    public $pagination;
    public $pdf;

    public function __construct()
    {
        \CI_Instance_Holder::$instance = $this;

        helper(['url', 'form', 'role', 'tanggal', 'whatsapp']);

        $this->load    = new \App\Libraries\CI3LoaderCompat($this);
        $this->session = new \App\Libraries\CI3SessionCompat();
        $this->input   = new \App\Libraries\CI3InputCompat();
        $this->db      = new \App\Libraries\CI3DBCompat();

        $uri = service('uri');
        $seg1 = $uri->getTotalSegments() >= 1 ? $uri->getSegment(1) : 'home';
        $seg2 = $uri->getTotalSegments() >= 2 ? $uri->getSegment(2) : 'index';

        $this->router = (object)[
            'class'  => $seg1,
            'method' => $seg2,
        ];
    }

    public function __get(string $name)
    {
        if ($name === 'db') return $this->db;
        if ($name === 'input') return $this->input;
        if ($name === 'session') return $this->session;
        if ($name === 'load') return $this->load;
        if ($name === 'upload') {
            if (!isset($this->upload)) {
                $this->upload = new \App\Libraries\CI3UploadCompat();
            }
            return $this->upload;
        }
        if ($name === 'pagination') {
            if (!isset($this->pagination)) {
                $this->pagination = new \App\Libraries\CI3PaginationCompat();
            }
            return $this->pagination;
        }
        if ($name === 'pdf') {
            if (!isset($this->pdf)) {
                $this->pdf = new \App\Libraries\Pdf();
            }
            return $this->pdf;
        }
        return null;
    }
}

if (!class_exists('App\Controllers\CI_Controller', false)) {
    class_alias('CI_Controller', 'App\Controllers\CI_Controller');
}
