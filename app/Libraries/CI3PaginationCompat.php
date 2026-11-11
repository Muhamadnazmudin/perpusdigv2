<?php

namespace App\Libraries;

class CI3PaginationCompat
{
    protected array $config = [
        'base_url'             => '',
        'total_rows'           => 0,
        'per_page'             => 10,
        'num_links'            => 2,
        'cur_page'             => 0,
        'use_page_numbers'     => false,
        'page_query_string'    => false,
        'query_string_segment' => 'per_page',
        'uri_segment'          => 3,
        'full_tag_open'        => '<ul class="pagination">',
        'full_tag_close'       => '</ul>',
        'first_link'           => '&lsaquo; First',
        'first_tag_open'       => '<li class="page-item">',
        'first_tag_close'      => '</li>',
        'last_link'            => 'Last &rsaquo;',
        'last_tag_open'        => '<li class="page-item">',
        'last_tag_close'       => '</li>',
        'first_url'            => '',
        'cur_tag_open'         => '<li class="page-item active"><span class="page-link">',
        'cur_tag_close'        => '</span></li>',
        'next_link'            => '&gt;',
        'next_tag_open'        => '<li class="page-item">',
        'next_tag_close'       => '</li>',
        'prev_link'            => '&lt;',
        'prev_tag_open'        => '<li class="page-item">',
        'prev_tag_close'       => '</li>',
        'num_tag_open'         => '<li class="page-item">',
        'num_tag_close'        => '</li>',
        'attributes'           => ['class' => 'page-link'],
        'reuse_query_string'   => false,
    ];

    public function __construct(array $params = [])
    {
        $this->initialize($params);
    }

    public function initialize(array $params = []): self
    {
        foreach ($params as $key => $val) {
            $this->config[$key] = $val;
        }
        return $this;
    }

    public function create_links(): string
    {
        $totalRows = (int)$this->config['total_rows'];
        $perPage   = (int)$this->config['per_page'];

        if ($totalRows === 0 || $perPage === 0 || $totalRows <= $perPage) {
            return '';
        }

        $numPages = (int)ceil($totalRows / $perPage);

        // Determine current page
        $curPage = 0;
        $request = service('request');

        if (!empty($this->config['page_query_string'])) {
            $seg = $this->config['query_string_segment'] ?: 'page';
            $val = $request->getGet($seg);
            if ($val !== null && is_numeric($val)) {
                $curPage = (int)$val;
            }
        } else {
            $uri = service('uri');
            $segNum = (int)($this->config['uri_segment'] ?: 3);
            if ($uri->getTotalSegments() >= $segNum) {
                $val = $uri->getSegment($segNum);
                if (is_numeric($val)) {
                    $curPage = (int)$val;
                }
            }
        }

        // If not using page numbers, curPage is offset
        $currentPage = 1;
        if (!empty($this->config['use_page_numbers'])) {
            $currentPage = max(1, min($numPages, $curPage ?: 1));
        } else {
            $currentPage = (int)floor($curPage / $perPage) + 1;
            $currentPage = max(1, min($numPages, $currentPage));
        }

        $attrStr = '';
        if (!empty($this->config['attributes'])) {
            foreach ($this->config['attributes'] as $k => $v) {
                $attrStr .= ' ' . $k . '="' . htmlspecialchars($v) . '"';
            }
        }

        $baseUrl = rtrim($this->config['base_url'], '/');
        $queryString = '';
        if (!empty($this->config['reuse_query_string'])) {
            $get = $request->getGet();
            if (!empty($this->config['page_query_string'])) {
                unset($get[$this->config['query_string_segment'] ?: 'page']);
            }
            if (!empty($get)) {
                $queryString = '?' . http_build_query($get);
            }
        }

        $buildUrl = function ($page) use ($baseUrl, $perPage, $queryString) {
            $offset = !empty($this->config['use_page_numbers']) ? $page : ($page - 1) * $perPage;
            if (!empty($this->config['page_query_string'])) {
                $seg = $this->config['query_string_segment'] ?: 'page';
                $delim = (strpos($queryString, '?') !== false) ? '&' : '?';
                return $baseUrl . $queryString . $delim . $seg . '=' . $offset;
            }
            return $baseUrl . '/' . $offset . $queryString;
        };

        $output = $this->config['full_tag_open'];

        // Prev link
        if ($currentPage > 1) {
            $prevUrl = $buildUrl($currentPage - 1);
            $output .= $this->config['prev_tag_open'] . '<a href="' . $prevUrl . '"' . $attrStr . '>' . $this->config['prev_link'] . '</a>' . $this->config['prev_tag_close'];
        }

        $numLinks = (int)$this->config['num_links'];
        $start = max(1, $currentPage - $numLinks);
        $end   = min($numPages, $currentPage + $numLinks);

        for ($i = $start; $i <= $end; $i++) {
            if ($i === $currentPage) {
                $output .= $this->config['cur_tag_open'] . $i . $this->config['cur_tag_close'];
            } else {
                $url = $buildUrl($i);
                $output .= $this->config['num_tag_open'] . '<a href="' . $url . '"' . $attrStr . '>' . $i . '</a>' . $this->config['num_tag_close'];
            }
        }

        // Next link
        if ($currentPage < $numPages) {
            $nextUrl = $buildUrl($currentPage + 1);
            $output .= $this->config['next_tag_open'] . '<a href="' . $nextUrl . '"' . $attrStr . '>' . $this->config['next_link'] . '</a>' . $this->config['next_tag_close'];
        }

        $output .= $this->config['full_tag_close'];
        return $output;
    }
}

