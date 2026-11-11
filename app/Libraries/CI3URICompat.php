<?php

namespace App\Libraries;

class CI3URICompat
{
    protected $uri;

    public function __construct()
    {
        try {
            $this->uri = service('uri');
        } catch (\Throwable $e) {
            $this->uri = null;
        }
    }

    public function segment(int $n, $no_result = null)
    {
        try {
            $uri = $this->uri ?: service('uri');
            if ($uri && $n > 0 && $uri->getTotalSegments() >= $n) {
                return $uri->getSegment($n);
            }
        } catch (\Throwable $e) {
        }
        return $no_result;
    }

    public function rsegment(int $n, $no_result = null)
    {
        return $this->segment($n, $no_result);
    }

    public function total_segments(): int
    {
        try {
            $uri = $this->uri ?: service('uri');
            return $uri ? $uri->getTotalSegments() : 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function segment_array(): array
    {
        try {
            $uri = $this->uri ?: service('uri');
            return $uri ? $uri->getSegments() : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function uri_string(): string
    {
        try {
            $uri = $this->uri ?: service('uri');
            return $uri ? $uri->getPath() : '';
        } catch (\Throwable $e) {
            return '';
        }
    }
}

