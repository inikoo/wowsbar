<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 14:52:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


return [
    'hosts'        => explode(',', env('ELASTICSEARCH_HOSTS', 'localhost:9200')),
    'api_key'      => env('ELASTICSEARCH_API_KEY'),
    'ca_bundle'    => env('ELASTICSEARCH_CA_BUNDLE'),
    'index_prefix' => env('ELASTICSEARCH_INDEX_PREFIX', 'wowsbar_'),

    'indices' => [
        'mappings' => [
            'universal_search' => [
                'properties' => [
                    'tenant_id'   => [
                        'type' => 'keyword',
                    ],
                    'section'     => [
                        'type' => 'keyword',
                    ],
                    'title'       => [
                        'type' => 'text',
                    ],
                    'description' => [
                        'type' => 'text',
                    ],
                ],
            ]
        ]
    ]

];
