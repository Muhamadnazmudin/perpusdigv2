<?php

namespace App\Libraries;

use CodeIgniter\View\View as BaseView;

class View extends BaseView
{
    public function __isset($name): bool
    {
        if (in_array($name, ['session', 'user', 'uri', 'input', 'security', 'config'], true)) {
            return true;
        }

        return parent::__isset($name);
    }

    public function __get($name)
    {
        if ($name === 'session') {
            return new class {
                public function flashdata($key)
                {
                    return session()->getFlashdata($key);
                }
                public function userdata($key = null)
                {
                    if ($key === null) {
                        return session()->get();
                    }
                    return session()->get($key);
                }
                public function set_userdata($key, $val = null)
                {
                    if (is_array($key)) {
                        session()->set($key);
                    } else {
                        session()->set($key, $val);
                    }
                }
            };
        }

        if ($name === 'user') {
            return session()->get('user') ?? [];
        }

        if ($name === 'uri') {
            return new class {
                public function segment($n)
                {
                    try {
                        $uri = service('uri');
                        if ($uri->getTotalSegments() >= $n && $n > 0) {
                            return $uri->getSegment($n);
                        }
                    } catch (\Throwable $e) {
                    }
                    return null;
                }
            };
        }

        if ($name === 'input') {
            return new class {
                public function get($key = null)
                {
                    $req = service('request');
                    return $key === null ? $req->getGet() : $req->getGet($key);
                }
                public function post($key = null)
                {
                    $req = service('request');
                    return $key === null ? $req->getPost() : $req->getPost($key);
                }
            };
        }

        if ($name === 'security') {
            return new class {
                public function get_csrf_token_name()
                {
                    return csrf_token();
                }
                public function get_csrf_hash()
                {
                    return csrf_hash();
                }
            };
        }

        if ($name === 'config') {
            return new class {
                public function item($key)
                {
                    if ($key === 'max_pinjam') {
                        return config('Perpus')->max_pinjam ?? 3;
                    }
                    return null;
                }
            };
        }

        return parent::__get($name);
    }
}
