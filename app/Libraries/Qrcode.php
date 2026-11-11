<?php

namespace App\Libraries;

if (!class_exists('\QRcode')) {
    require_once __DIR__ . '/phpqrcode/phpqrcode.php';
}

class Qrcode
{
    public function generate($text, $filepath, $size = 6, $level = null)
    {
        $level = $level ?? (defined('QR_ECLEVEL_L') ? constant('QR_ECLEVEL_L') : 'L');
        \QRcode::png($text, $filepath, $level, $size);
    }
}

