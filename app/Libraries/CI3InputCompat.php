<?php

namespace App\Libraries;

class CI3InputCompat
{
    protected $request;

    public function __construct()
    {
        $this->request = service('request');
    }

    public function post($key = null, $xss_clean = false)
    {
        if ($key === null) {
            return $this->request->getPost();
        }
        return $this->request->getPost($key);
    }

    public function get($key = null, $xss_clean = false)
    {
        if ($key === null) {
            return $this->request->getGet();
        }
        return $this->request->getGet($key);
    }

    public function get_post($key = null, $xss_clean = false)
    {
        if ($key === null) {
            return array_merge((array)$this->request->getGet(), (array)$this->request->getPost());
        }
        return $this->request->getPost($key) ?? $this->request->getGet($key);
    }

    public function server($key = null)
    {
        if ($key === null) {
            return $this->request->getServer();
        }
        return $this->request->getServer($key);
    }

    public function is_ajax_request(): bool
    {
        return $this->request->isAJAX();
    }

    public function method($upper = false): string
    {
        $m = $this->request->getMethod();
        return $upper ? strtoupper($m) : strtolower($m);
    }
}

