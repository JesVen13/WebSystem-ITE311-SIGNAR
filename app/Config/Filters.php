<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'    => \CodeIgniter\Filters\CSRF::class,
        'toolbar' => \CodeIgniter\Filters\DebugToolbar::class,
<<<<<<< HEAD
        'security' => \App\Filters\SecurityFilter::class,
=======
        // Add role alias here:
>>>>>>> c3cd521911bcd31f5d0997904ea5026bc1bd85f7
        'role'    => \App\Filters\RoleAuth::class,
    ];

    public $globals = [
        'before' => [
<<<<<<< HEAD
            'csrf',
            'security',
=======
            // 'csrf',
>>>>>>> c3cd521911bcd31f5d0997904ea5026bc1bd85f7
        ],
        'after' => [
            'toolbar',
        ],
    ];

    public $methods = [];
    public $filters = [];
}
