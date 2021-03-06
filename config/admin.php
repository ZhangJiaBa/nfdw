<?php

return [

    /*
     * Laravel-admin name.
     */
    'name'      => '南方电网考勤管理系统',

    /*
     * Logo in admin panel header.
     */
    'logo'      => '南方电网考勤管理系统',

    /*
     * Mini-logo in admin panel header.
     */
    'logo-mini' => '',

    /*
     * Laravel-admin url prefix.
     */
    'prefix'    => '',

    /*
     * Laravel-admin install directory.
     */
    'directory' => app_path('Admin'),

    /*
     * Laravel-admin html title.
     */
    'title'  => env('ADMIN_TITLE', '南方电网考勤管理系统'),



    /*
     * Use `https`.
     */
    'secure' => false,

    /*
     * Laravel-admin auth setting.
     */
    'auth' => [
        'guards' => [
            'admin' => [
                'driver'   => 'session',
                'provider' => 'admin',
            ],
        ],

        'providers' => [
            'admin' => [
                'driver' => 'eloquent',
                'model'  => \App\Models\Admin\Administrator::class,
            ],
        ],
    ],

    /*
     * Laravel-admin upload setting.
     */
    'upload' => [

        'disk' => 'admin',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
        'host' => '',
    ],

    /*
     * Laravel-admin database setting.
     */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'users',
        'users_model' => Encore\Admin\Auth\Database\Administrator::class,

        // Role table and model.
        'roles_table' => 'roles',
        'roles_model' => Encore\Admin\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'permissions',
        'permissions_model' => Encore\Admin\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'menu',
        'menu_model' => Encore\Admin\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'operation_log',
        'user_permissions_table' => 'user_permissions',
        'role_users_table'       => 'role_users',
        'role_permissions_table' => 'role_permissions',
        'role_menu_table'        => 'role_menu',
    ],

    /*
     * By setting this option to open or close operation log in laravel-admin.
     */
    'operation_log' => [

        'enable' => true,

        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs
         */
        'except' => [
            'admin/auth/logs*',
        ],
    ],

    /*
     * @see https://adminlte.io/docs/2.4/layout
     */
    'skin' => 'skin-blue-light',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout' => ['fixed'],

    /*
     * Version displayed in footer.
     */
    'version' => '1.5.x-dev',

    /*
     * Settings for extensions.
     */
    'extensions' => [

    ],
];
