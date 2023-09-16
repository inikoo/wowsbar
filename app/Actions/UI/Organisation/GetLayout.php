<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation;

use App\Models\Organisation\Market\Shop;
use App\Models\Organisation\OrganisationUser;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLayout
{
    use AsAction;

    public function handle(OrganisationUser $user): array
    {
        $navigation = [];

        $organisation = organisation();


        $shopsCount = $organisation->stats->number_shops;
        $shop       = null;
        if ($shopsCount == 1) {
            /** @var Shop $shop */
            $shop = $organisation->shops()->first();
        }


        if ($user->can('shops')) {
            $navigation['shops'] = [
                'label' => __('shops'),
                'icon'  => ['fal', 'fa-store-alt'],
                'route' =>
                    match ($shopsCount) {
                        1 => [
                            'name'       => 'org.shops.show',
                            'parameters' => $shop->slug
                        ],
                        default => [
                            'name' => 'org.shops.index'
                        ],
                    },

                'topMenu' => [
                    'subSections' =>
                        match ($shopsCount) {
                            1 =>
                            [
                                [

                                    'label' => __('departments'),
                                    'icon'  => ['fal', 'fa-folder-tree'],
                                    'route' => [
                                        'name'       => 'org.shops.show.departments.index',
                                        'parameters' => $shop->slug
                                    ]
                                ],
                                [

                                    'label' => __('products'),
                                    'icon'  => ['fal', 'fa-cube'],
                                    'route' => [
                                        'name'       => 'org.shops.show.products.index',
                                        'parameters' => $shop->slug
                                    ]
                                ]
                            ],
                            default => []
                        }


                    /*
                       [
                        'label' => __('shops'),
                        'icon'  => ['fal', 'fa-store-alt'],
                        'route' => [
                            'name' => 'org.shops.index',
                        ]
                    ],
                    [
                        'label' => __('webpages'),
                        'icon'  => ['fal', 'fa-browser'],
                        'route' => [
                            'name' => 'org.website.webpages.index',
                        ]
                    ],
                    */

                ]
            ];
        }


        if ($user->can('websites') and $organisation->stats->number_shops > 0) {
            $navigation['websites'] = [
                'label'   => __('websites'),
                'icon'    => ['fal', 'fa-globe'],
                'route'   => [
                    'name' => 'org.websites.index'
                ],
                'topMenu' => [
                    'subSections' => [
                        [
                            'label' => __('websites'),
                            'icon'  => ['fal', 'fa-globe'],
                            'route' => [
                                'name' => 'org.websites.index',
                            ]
                        ],
                        /*
                        [
                            'label' => __('webpages'),
                            'icon'  => ['fal', 'fa-browser'],
                            'route' => [
                                'name' => 'org.website.webpages.index',
                            ]
                        ],
                        */
                    ]
                ]
            ];
        }

        if ($user->can('crm.view') and $organisation->stats->number_shops > 0) {
            $navigation['crm'] = [
                'label' => __('Customers'),
                'icon'  => ['fal', 'fa-user'],

                'route'   => [
                    'name' => 'org.crm.dashboard'
                ],
                'topMenu' => [
                    'subSections' => [

                    ]
                ]

            ];
        }

        if ($user->can('accounting.view')) {
            $navigation['accounting'] = [
                'label'   => __('Accounting'),
                'icon'    => ['fal', 'fa-abacus'],
                'route'   => [
                    'name' => 'org.accounting.dashboard'
                ],
                'topMenu' => [
                    'subSections' => [

                    ]
                ]


            ];
        }

        if ($user->can('hr')) {
            $navigation['hr'] = [
                'label'   => __('human resources'),
                'icon'    => ['fal', 'fa-user-hard-hat'],
                'route'   => [
                    'name' => 'org.hr.dashboard'
                ],
                'topMenu' => [
                    'subSections' => [
                        [
                            'label' => __('job positions'),
                            'icon'  => ['fal', 'fa-network-wired'],
                            'route' => [
                                'name' => 'org.hr.job-positions.index',

                            ]
                        ],
                        [
                            'label' => __('employees'),
                            'icon'  => ['fal', 'fa-user-hard-hat'],
                            'route' => [
                                'name' => 'org.hr.employees.index',

                            ]
                        ],
                        [
                            'label' => __('calendar'),
                            'icon'  => ['fal', 'fa-calendar'],
                            'route' => [
                                'name' => 'org.hr.calendars.index',

                            ]
                        ],
                        [
                            'label' => __('time sheets'),
                            'icon'  => ['fal', 'fa-stopwatch'],
                            'route' => [
                                'name' => 'org.hr.time-sheets.index',

                            ]
                        ],
                        [
                            'label' => __('working place'),
                            'icon'  => ['fal', 'fa-building'],
                            'route' => [
                                'name' => 'org.hr.working-places.index',

                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($user->can('sysadmin')) {
            $navigation['sysadmin'] = [
                'label'   => __('sysadmin'),
                'icon'    => ['fal', 'fa-users-cog'],
                'route'   =>
                    ['name' => 'org.sysadmin.dashboard'],
                'topMenu' => [
                    'subSections' => [
                        [
                            'label' => __('users'),
                            'icon'  => ['fal', 'fa-terminal'],
                            'route' => [
                                'name' => 'org.sysadmin.users.index',

                            ]
                        ],
                        [
                            'label' => __('system settings'),
                            'icon'  => ['fal', 'fa-cog'],
                            'route' => [
                                'name' => 'org.sysadmin.organisation.edit',

                            ]
                        ],
                    ]
                ]
            ];
        }


        return [
            'navigation' => $navigation,
        ];
    }
}
