<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * always loaded, and returned values will be available
 * throughout the system.
 *
 * @see https://codeigniter.com/user_guide/extending/common.html
 */

spl_autoload_register(function ($class) {
    if ($class === 'CI_Controller' || $class === 'App\\Controllers\\CI_Controller') {
        require_once APPPATH . 'Controllers/CI_Controller.php';
    } elseif ($class === 'MY_Controller' || $class === 'App\\Controllers\\MY_Controller') {
        require_once APPPATH . 'Controllers/MY_Controller.php';
    } elseif ($class === 'CI_Model' || $class === 'App\\Models\\CI_Model') {
        require_once APPPATH . 'Models/CI_Model.php';
    }
});

class CI_Instance_Holder
{
    public static $instance = null;
}

if (! function_exists('get_instance')) {
    function &get_instance()
    {
        if (CI_Instance_Holder::$instance !== null) {
            return CI_Instance_Holder::$instance;
        }

        static $dummy = null;
        if ($dummy === null) {
            $dummy = new \App\Libraries\CI3InstanceCompat();
        }
        return $dummy;
    }
}

if (! function_exists('redirect')) {
    function redirect($uri = '', $method = 'auto', $code = null)
    {
        if ($uri === null || $uri === '') {
            return service('redirectresponse');
        }

        $url = (strpos($uri, '://') !== false) ? $uri : site_url($uri);

        if (!headers_sent()) {
            header('Location: ' . $url, true, $code ?: 302);
            exit;
        }

        echo "<script>window.location.href='" . addslashes($url) . "';</script>";
        exit;
    }
}

if (! function_exists('show_error')) {
    function show_error($message, $status = 500, $heading = 'An Error Was Encountered')
    {
        if ($status === 403) {
            header('HTTP/1.1 403 Forbidden', true, 403);
            echo "<h1>403 Forbidden</h1><p>" . htmlspecialchars((string)$message) . "</p>";
            exit;
        }

        header("HTTP/1.1 {$status} Error", true, $status);
        echo "<h1>{$heading}</h1><p>" . htmlspecialchars((string)$message) . "</p>";
        exit;
    }
}

if (! function_exists('show_404')) {
    function show_404($page = '', $log_error = true)
    {
        header('HTTP/1.1 404 Not Found', true, 404);
        echo "<h1>404 Page Not Found</h1><p>" . htmlspecialchars((string)$page) . "</p>";
        exit;
    }
}

if (! function_exists('force_download')) {
    function force_download($filename = '', $data = '', $set_mime = false)
    {
        if (empty($filename)) {
            return;
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $mime = 'application/octet-stream';
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($ext === 'zip') {
            $mime = 'application/zip';
        } elseif ($ext === 'pdf') {
            $mime = 'application/pdf';
        } elseif ($ext === 'xls') {
            $mime = 'application/vnd.ms-excel';
        } elseif ($ext === 'xlsx') {
            $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }

        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . strlen($data));

        echo $data;
        exit;
    }
}
