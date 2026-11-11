<?php

namespace App\Libraries;

class CI3SessionCompat
{
    protected $session;

    public function __construct()
    {
        $this->session = service('session');
    }

    public function userdata($key = null)
    {
        if ($key === null) {
            return $this->session->get();
        }
        return $this->session->get($key);
    }

    public function set_userdata($key, $val = null): void
    {
        if (is_array($key)) {
            $this->session->set($key);
        } else {
            $this->session->set($key, $val);
        }
    }

    public function unset_userdata($key): void
    {
        $this->session->remove($key);
    }

    public function flashdata($key = null)
    {
        return $this->session->getFlashdata($key);
    }

    public function set_flashdata($key, $val = null): void
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->session->setFlashdata($k, $v);
            }
        } else {
            $this->session->setFlashdata($key, $val);
        }
    }

    public function sess_destroy(): void
    {
        $this->session->destroy();
    }

    public function __get(string $name)
    {
        return $this->userdata($name);
    }

    public function __call(string $method, array $args)
    {
        if (method_exists($this->session, $method)) {
            return $this->session->{$method}(...$args);
        }
        return null;
    }
}

