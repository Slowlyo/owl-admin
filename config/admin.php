<?php

return [
    // 应用名称
    'name'           => 'Slow Admin',

    // 应用 logo
    'logo'           => '/admin/logo.png',

    // 默认头像
    'default_avatar' => '/admin/default-avatar.png',

    'directory' => app_path('Admin'),

    'bootstrap' => app_path('Admin/bootstrap.php'),

    'route' => [
        'prefix'     => 'admin-api',
        'domain'     => null,
        'namespace'  => 'App\\Admin\\Controllers',
        'middleware' => ['admin'],
    ],

    'auth' => [
        'login_captcha' => true,
        'enable'        => true,
        'controller'    => \Slowlyo\SlowAdmin\Controllers\AuthController::class,
        'guard'         => 'sanctum',
        'model'         => \Slowlyo\SlowAdmin\Models\AdminUser::class,
        'except'        => [

        ],
    ],

    'upload' => [
        'disk'      => 'public',
        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file'  => 'files',
            'rich'  => 'rich',
        ],
    ],

    'https' => false,

    'show_development_tools'               => true,

    // 是否显示 [权限] 功能中的自动生成按钮
    'show_auto_generate_permission_button' => true,

    'extension' => [
        'dir' => base_path('extensions'),
    ],
];
