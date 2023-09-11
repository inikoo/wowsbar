<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local'    => [
            'driver' => 'local',
            'root'   => storage_path('app'),
            'throw'  => false,
        ],
        'datasets' => [
            'driver' => 'local',
            'root'   => database_path('seeders/datasets'),
            'throw'  => false,
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw'      => false,
        ],
        'media'  => [
            'driver' => 'local',
            'root'   => storage_path('app/media'),
            //    'url'        => env('APP_URL').'/tenants/storage',
            'throw'  => false,
        ],

        'r2' => [
            'driver'   => 's3',
            'key'      => env('CLOUDFLARE_R2_ACCESS_KEY'),
            'secret'   => env('CLOUDFLARE_R2_SECRET_KEY'),
            'region'   => env('CLOUDFLARE_R2_REGION'),
            'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
            'bucket'   => env('CLOUDFLARE_R2_BUCKET_NAME'),
        ],

        'media-r2' => [
            'driver'   => 's3',
            'key'      => env('CLOUDFLARE_R2_ACCESS_KEY'),
            'secret'   => env('CLOUDFLARE_R2_SECRET_KEY'),
            'region'   => env('CLOUDFLARE_R2_REGION'),
            'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
            'bucket'   => env('CLOUDFLARE_R2_MEDIA_BUCKET_NAME'),
        ],



    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        //public_path('storage') => storage_path('app/public'),
        public_path('images') => resource_path('images/'),

    ],

];
