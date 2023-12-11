<?php


use App\Models\Helpers\Audit;

return [

    'enabled' => env('AUDITING_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Audit Implementation
    |--------------------------------------------------------------------------
    |
    | Define which Audit model implementation should be used.
    |
    */

    'implementation' => Audit::class,

    /*
    |--------------------------------------------------------------------------
    | User Morph prefix & Guards
    |--------------------------------------------------------------------------
    |
    | Define the morph prefix and authentication guards for the User resolver.
    |
    */

    'user'      => [
        'morph_prefix' => 'user',
        'guards'       => [
            'web',
            'api',
            'org',
            'customer'
        ],
        'resolver'     => \App\AuditResolvers\AuditUserResolver::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Resolvers
    |--------------------------------------------------------------------------
    |
    | Define the IP Address, User Agent and URL resolver implementations.
    |
    */
    'resolvers' => [
        'ip_address'       => OwenIt\Auditing\Resolvers\IpAddressResolver::class,
        'user_agent'       => OwenIt\Auditing\Resolvers\UserAgentResolver::class,
        'url'              => OwenIt\Auditing\Resolvers\UrlResolver::class,
        'customer_id'      => \App\AuditResolvers\AuditCustomerResolver::class,
        'customer_user_id' => \App\AuditResolvers\AuditCustomerUserResolver::class,
        'shop_id'          => \App\AuditResolvers\AuditShopResolver::class,
        'website_id'       => \App\AuditResolvers\AuditWebsiteResolver::class,
        'bulk_id'          => \App\AuditResolvers\AuditBulkIdResolver::class,
        'bulk_type'        => \App\AuditResolvers\AuditBulkTypeResolver::class,
        'created_at'       => \App\AuditResolvers\AuditCreatedAtResolver::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Events
    |--------------------------------------------------------------------------
    |
    | The Eloquent events that trigger an Audit.
    |
    */

    'events' => [
        'created',
        'updated',
        'deleted',
        'restored'
    ],

    /*
    |--------------------------------------------------------------------------
    | Strict Mode
    |--------------------------------------------------------------------------
    |
    | Enable the strict mode when auditing?
    |
    */

    'strict' => true,

    /*
    |--------------------------------------------------------------------------
    | Global exclude
    |--------------------------------------------------------------------------
    |
    | Have something you always want to exclude by default? - add it here.
    | Note that this is overwritten (not merged) with local exclude
    |
    */

    'exclude' => ['id', 'password', 'slug', 'created_at', 'updated_at', 'uuid'],

    /*
    |--------------------------------------------------------------------------
    | Empty Values
    |--------------------------------------------------------------------------
    |
    | Should Audit records be stored when the recorded old_values & new_values
    | are both empty?
    |
    | Some events may be empty on purpose. Use allowed_empty_values to exclude
    | those from the empty values check. For example when auditing
    | model retrieved events which will never have new and old values.
    |
    |
    */

    'empty_values'         => false,
    'allowed_empty_values' => [
        'retrieved',
        'created'
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Timestamps
    |--------------------------------------------------------------------------
    |
    | Should the created_at, updated_at and deleted_at timestamps be audited?
    |
    */

    'timestamps' => false,

    /*
    |--------------------------------------------------------------------------
    | Audit Threshold
    |--------------------------------------------------------------------------
    |
    | Specify a threshold for the amount of Audit records a model can have.
    | Zero means no limit.
    |
    */

    'threshold' => 0,

    /*
    |--------------------------------------------------------------------------
    | Audit Driver
    |--------------------------------------------------------------------------
    |
    | The default audit driver used to keep track of changes.
    |
    */

    'driver' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Audit Driver Configurations
    |--------------------------------------------------------------------------
    |
    | Available audit drivers and respective configurations.
    |
    */

    'drivers' => [
        'database' => [
            'table'      => 'audits',
            'connection' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Console
    |--------------------------------------------------------------------------
    |
    | Whether console events should be audited (e.g. php artisan db:seed).
    |
    */

    'console' => true,
];
