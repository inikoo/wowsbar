<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 15:43:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

return [


    'positions' => [

        'dir'   => [
            'code'       => 'dir',
            'name'       => 'Director',
            'department' => 'admin',
            'roles'      => [
                'super-admin'
            ]
        ],
        'hr-m'  => [
            'code'       => 'hr-m',
            'grade'      => 'manager',
            'department' => 'admin',
            'name'       => 'Human resources supervisor',
            'roles'      => [
                'human-resources-supervisor'
            ]
        ],
        'hr-c'  => [
            'code'       => 'hr-c',
            'name'       => 'Human resources clerk',
            'department' => 'admin',
            'grade'      => 'clerk',
            'roles'      => [
                'human-resources'
            ]
        ],
        'acc'   => [
            'code'       => 'acc',
            'department' => 'admin',
            'name'       => 'Accounts',
            'roles'      => [
                'accounting'
            ]
        ],
        'mrk-m' => [
            'code'       => 'mrk-m',
            'grade'      => 'manager',
            'department' => 'marketing',
            'name'       => 'Marketing supervisor',
            'roles'      => [
                'services-manager'
            ]
        ],
        'mrk-c' => [
            'code'       => 'mrk-c',
            'grade'      => 'clerk',
            'department' => 'marketing',
            'name'       => 'Marketing clerk',
            'roles'      => [
                'services'
            ]
        ],
        'web-m' => [
            'code'       => 'web-m',
            'grade'      => 'manager',
            'department' => 'marketing',
            'name'       => 'Webmaster supervisor',
            'roles'      => [
                'web-master'
            ]
        ],

        'web-c' => [
            'code'       => 'web-c',
            'grade'      => 'clerk',
            'department' => 'marketing',
            'name'       => 'Webmaster clerk',
            'roles'      => [
                'web-editor'
            ]
        ],


        'seo-m'       => [
            'code'       => 'seo-m',
            'team'       => 'seo',
            'department' => 'content',
            'name'       => 'Seo supervisor',
            'roles'      => [
                'seo-supervisor'
            ]
        ],
        'seo-w'       => [
            'code'       => 'seo-w',
            'team'       => 'seo',
            'department' => 'content',
            'name'       => 'SEO',
            'roles'      => [
                'seo'
            ]
        ],
        'ppc-m'       => [
            'code'       => 'ppc-m',
            'team'       => 'ppc',
            'department' => 'content',
            'name'       => 'PPC supervisor',
            'roles'      => [
                'google-ads-supervisor'
            ]
        ],
        'ppc-w'       => [
            'code'       => 'ppc-w',
            'team'       => 'ppc',
            'department' => 'content',
            'name'       => 'PPC',
            'roles'      => [
                'google-ads'
            ]
        ],
        'social-m'    => [
            'code'       => 'social-m',
            'team'       => 'social',
            'department' => 'content',
            'name'       => 'Social media supervisor',
            'roles'      => [
                'social-supervisor'
            ]
        ],
        'social-w'    => [
            'code'       => 'social-w',
            'team'       => 'social',
            'department' => 'content',
            'name'       => 'Social media',
            'roles'      => [
                'social'
            ]
        ],
        'developer-m' => [
            'code'       => 'dev-m',
            'team'       => 'developer',
            'department' => 'development',
            'name'       => 'Developer supervisor',
            'roles'      => [
                'guest'
            ]
        ],
        'developer-w' => [
            'code'       => 'dev-w',
            'team'       => 'developer',
            'department' => 'development',
            'name'       => 'Developer',
            'roles'      => [
                'guest'
            ]
        ],
        'cus-m'       => [
            'code'       => 'cus-m',
            'grade'      => 'manager',
            'department' => 'customer-services',
            'name'       => 'Customer service supervisor',
            'roles'      => [
                'customer-services-supervisor'
            ]
        ],
        'cus-c'       => [
            'code'       => 'cus-c',
            'grade'      => 'clerk',
            'department' => 'customer-services',
            'name'       => 'Customer service',
            'roles'      => [
                'customer-services'
            ]
        ],
    ],
    'wrappers'  => [
        'hr'  => ['hr-m', 'hr-c'],
        'mrk' => ['mrk-m', 'mrk-c'],
        'cus' => ['cus-m', 'cus-c']
    ],

    'blueprint' => [
        'management' => [
            'title'     => 'management and operations',
            'positions' => [
                'dir' => 'dir',
                'acc' => 'acc',
                'buy' => 'buy',
                'hr'  => ['hr-m', 'hr-c'],


            ]
        ],
        'marketing'  => [
            'title'     => 'marketing and customer services',
            'positions' => [
                'mrk' => ['mrk-m', 'mrk-c'],
                'cus' => ['cus-m', 'cus-c'],


            ],
            'scope'     => 'shops'
        ],
        'inventory'  => [
            'title'     => 'warehousing',
            'positions' => [


            ],
            'scope'     => 'warehouses'
        ],

    ]
];
