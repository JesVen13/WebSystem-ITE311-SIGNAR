<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'    => \CodeIgniter\Filters\CSRF::class,
        'toolbar' => \CodeIgniter\Filters\DebugToolbar::class,
        'security' => \App\Filters\SecurityFilter::class,
        'role'    => \App\Filters\RoleAuth::class,
    ];

    public $globals = [
        'before' => [
            'csrf',
            'security',
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public $methods = [];
    public $filters = [];
}
