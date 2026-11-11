<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use Config\View as ViewConfig;

class Services extends BaseService
{
    public static function renderer(?string $viewPath = null, $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('renderer', $viewPath, $config);
        }

        $viewPath = in_array($viewPath, [null, '', '0'], true) ? (new Paths())->viewDirectory : $viewPath;
        $config ??= config('View');

        return new \App\Libraries\View($config, $viewPath, static::get('locator'), CI_DEBUG, static::get('logger'));
    }
}
