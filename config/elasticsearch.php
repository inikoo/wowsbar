<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 14:52:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


return [
    'host'         => env('ELASTICSEARCH_HOST'),
    'api_key'      => env('ELASTICSEARCH_API_KEY'),
    'ca_bundle'    => env('ELASTICSEARCH_CA_BUNDLE'),
    'index_prefix' => env('ELASTICSEARCH_INDEX_PREFIX', 'wowsbar').'_'.env('APP_ENV', 'production').'_',

    'indices' => [
        'mappings' => [
            'universal_search' => [
                'properties' => [
                    'in_organisation' => [
                        'type' => 'boolean',
                    ],
                    'shop_id'         => [
                        'type' => 'keyword',
                    ],
                    'website_id'      => [
                        'type' => 'keyword',
                    ],
                    'customer_id'     => [
                        'type' => 'keyword',
                    ],
                    'section'         => [
                        'type' => 'keyword',
                    ],
                    'title'           => [
                        'type' => 'text',
                    ],
                    'description'     => [
                        'type' => 'text',
                    ],
                ],
            ]
        ]
    ]

];
