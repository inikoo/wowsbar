<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation;

use App\Models\Auth\OrganisationUser;
use App\Models\Market\Shop;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLayout
{
    use AsAction;

    public function handle(OrganisationUser $user): array
    {
        $navigation = [];

        $organisation = organisation();


        $shopsCount    = $organisation->stats->number_shops;
        $websitesCount = $organisation->stats->number_websites;

        $shop    = null;
        $website = null;
        if ($shopsCount == 1) {
            /** @var Shop $shop */
            $shop = $organisation->shops()->first();
        }
        if ($websitesCount == 1) {
            /** @var Website $website */
            $website = $organisation->websites()->first();
        }


        if ($user->can('shops.view')) {
            $navigation['shops'] = [
                'label' => $shopsCount == 1 ? __('shop') : __('shops'),
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
                ]
            ];
        }

        if ($user->can('websites.view') and $shopsCount > 0) {
            if ($shopsCount == 1) {
                if ($shop->website) {
                    $route = [
                        'name'       => 'org.websites.show',
                        'parameters' => $shop->website->slug
                    ];
                } else {
                    $route = [
                        'name'       => 'org.shops.show.website.create',
                        'parameters' => $shop->slug
                    ];
                }
            } else {
                $route = [
                    'name' => 'org.websites.index'
                ];
            }

            $subSections=[];
            if($websitesCount==1) {
                $subSections[]=[
                    'label' => __('webpages'),
                    'icon'  => ['fal', 'fa-browser'],
                    'route' => [
                        'name'       => 'org.websites.show.webpages.index',
                        'parameters' => $website->slug
                    ]
                ];
            }

            $navigation['websites'] = [
                'label'   => $websitesCount==1 ? __('website') : __('websites'),
                'icon'    => ['fal', 'fa-globe'],
                'route'   => $route,
                'topMenu' => [
                    'subSections' => $subSections
                ]
            ];
        }

        if ($user->can('crm.view') and $organisation->stats->number_shops > 0) {
            $navigation['crm'] = [
                'label' => __('Customers'),
                'icon'  => ['fal', 'fa-user'],
                'route' =>
                    match ($shopsCount) {
                        1 => [
                            'name'       => 'org.crm.shop.dashboard',
                            'parameters' => $shop->slug
                        ],
                        default => [
                            'name' => 'org.crm.dashboard'
                        ],
                    },
                'topMenu' => [
                    'subSections' =>
                        match ($shopsCount) {
                            1 =>
                            [
                                [

                                    'label' => __('customers'),
                                    'icon'  => ['fal', 'fa-user'],
                                    'route' => [
                                        'name'       => 'org.crm.shop.customers.index',
                                        'parameters' => $shop->slug
                                    ]
                                ],
                                [

                                    'label' => __('prospects'),
                                    'icon'  => ['fal', 'fa-transporter'],
                                    'route' => [
                                        'name'       => 'org.crm.shop.prospects.index',
                                        'parameters' => $shop->slug
                                    ]
                                ],
                                [

                                    'label' => __('mailroom'),
                                    'icon'  => ['fal', 'fa-envelope'],
                                    'route' => [
                                        'name'       => 'org.crm.shop.mailroom.dashboard',
                                        'parameters' => $shop->slug
                                    ]
                                ]
                            ],
                            default => []
                        }
                    ]
            ];
        }

        if ($user->can('crm.view')) {
            $navigation['portfolio-websites'] = [
                'scope'   => 'portfolio-websites',
                'icon'    => ['fal', 'fa-briefcase'],
                'label'   => __('Customer Websites'),
                'route'   => [
                    'name'       => 'org.portfolio-websites.index',
                    'parameters' => []
                ],
                'topMenu' => [
                    'subSections' => []
                ]
            ];
        }

        if ($user->can('products.seo')) {
            $navigation['seo'] = [
                'label'   => __('SEO'),
                'icon'    => ['fab', 'fa-google'],
                'route'   => [
                    'name' => 'org.products.seo.dashboard'
                ],
                'topMenu' => [
                    'subSections' => [

                    ]
                ]
            ];
        }

        if ($user->can('products.google-ads')) {
            $navigation['google-ads'] = [
                'label'   => __('Ads'),
                'icon'    => ['fal', 'fa-ad'],
                'route'   => [
                    'name' => 'org.products.google-ads.dashboard'
                ],
                'topMenu' => [
                    'subSections' => [

                    ]
                ]
            ];
        }

        if ($user->can('products.social')) {
            $navigation['social'] = [
                'label'   => __('Social'),
                'icon'    => ['fal', 'fa-thumbs-up'],
                'route'   => [
                    'name' => 'org.products.social.dashboard'
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

        if ($user->can('hr.view')) {
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
                                'name' => 'org.hr.workplaces.index',

                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($user->can('sysadmin.view')) {
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
