<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    |
    | By uncommenting the Laravel Echo configuration, you may connect Filament
    | to any Pusher-compatible websockets server.
    |
    | This will allow your users to receive real-time notifications.
    |
    */

    'theme' => [

        'colors' => [
            'sidebar' => 'bg-gray-800 text-white', // Barra lateral (fondo y texto).
            'navbar' => 'bg-blue-600 text-white',  // Barra superior (fondo y texto).
        ],
    ],
    'broadcasting' => [

        // 'echo' => [
        //     'broadcaster' => 'pusher',
        //     'key' => env('VITE_PUSHER_APP_KEY'),
        //     'cluster' => env('VITE_PUSHER_APP_CLUSTER'),
        //     'wsHost' => env('VITE_PUSHER_HOST'),
        //     'wsPort' => env('VITE_PUSHER_PORT'),
        //     'wssPort' => env('VITE_PUSHER_PORT'),
        //     'authEndpoint' => '/broadcasting/auth',
        //     'disableStats' => true,
        //     'encrypted' => true,
        //     'forceTLS' => true,
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Filament will use to store files. You may use
    | any of the disks defined in the `config/filesystems.php`.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Assets Path
    |--------------------------------------------------------------------------
    |
    | This is the directory where Filament's assets will be published to. It
    | is relative to the `public` directory of your Laravel application.
    |
    | After changing the path, you should run `php artisan filament:assets`.
    |
    */

    'assets_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Cache Path
    |--------------------------------------------------------------------------
    |
    | This is the directory that Filament will use to store cache files that
    | are used to optimize the registration of components.
    |
    | After changing the path, you should run `php artisan filament:cache-components`.
    |
    */

    'cache_path' => base_path('bootstrap/cache/filament'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Loading Delay
    |--------------------------------------------------------------------------
    |
    | This sets the delay before loading indicators appear.
    |
    | Setting this to 'none' makes indicators appear immediately, which can be
    | desirable for high-latency connections. Setting it to 'default' applies
    | Livewire's standard 200ms delay.
    |
    */

    'livewire_loading_delay' => 'default',

    'layout' => [
        'templates' => [
            'action-section' => 'filament::components.layouts.app.action-section',
            'content' => 'filament::components.layouts.app.content',
            'footer' => 'filament::components.layouts.app.footer',
            'header' => 'filament::components.layouts.app.header',
            'sidebar' => 'filament::components.layouts.app.sidebar',
        ],
    ],

    'assets' => [
        'css' => [
            'resources/css/app.css',
        ],
        'js' => [
            'resources/js/app.js',
        ],
    ],

    'brand' => [
        'logo' => fn() => \App\Models\Empresa::first()->logo,
        'name' => 'El gordito',
        // También puedes usar
        // 'logo_url' => fn () => \App\Models\Empresa::first()->logo,
    ],

    //'favicon' => public_path('images/logo_sucursal.jpg'),

];
