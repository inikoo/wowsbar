<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 13:56:17 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer;

use App\Models\Auth\CustomerUser;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLayout
{
    use AsAction;

    public function handle(CustomerUser $customerUser): array
    {
        $navigation = [];


        $navigation['dashboard'] = [
            'scope'   => 'dashboard',
            'icon'    => ['fal', 'fa-home'],
            'label'   => __('Home'),
            'route'   => 'customer.dashboard.show',
            'topMenu' => [
                'subSections' => [],
            ]


        ];

        if ($customerUser->hasPermissionTo('portfolio.view')) {
            $navigation['portfolio'] = [
                'scope'   => 'portfolio',
                'icon'    => ['fal', 'fa-briefcase'],
                'label'   => __('portfolio'),
                'route'   => 'customer.portfolio.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-briefcase'],
                            'route' => [
                                'name' => 'customer.portfolio.dashboard',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.portfolio.websites.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-user'],
                            'label' => __('social media account'),
                            'route' => [
                                'name' => 'customer.portfolio.social.account.index',
                            ]
                        ],


                    ],

                ]


            ];
        }

        if ($customerUser->hasPermissionTo('portfolio.prospects.view')) {
            $navigation['prospects'] = [
                'scope'   => 'prospects',
                'icon'    => ['fal', 'fa-transporter'],
                'label'   => __('Leads'),
                'route'   => 'customer.prospects.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.prospects.websites.index',
                            ]
                        ],
                    ],
                ]
            ];
        }

        if ($customerUser->hasPermissionTo('portfolio.seo.view')) {
            $navigation['seo'] = [
                'scope'   => 'seo',
                'icon'    => ['fab', 'fa-google'],
                'label'   => __('SEO'),
                'route'   => 'customer.seo.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.seo.websites.index',
                            ]
                        ],
                    ],
                ]
            ];
        }

        if ($customerUser->hasPermissionTo('portfolio.google-ads.view')) {
            $navigation['google-ads'] = [
                'scope'   => 'google-ads',
                'icon'    => ['fal', 'fa-bullseye'],
                'label'   => __('Google Ads'),
                'route'   => 'customer.google-ads.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.google-ads.websites.index',
                            ]
                        ],
                    ],
                ]
            ];
        }

        if ($customerUser->hasPermissionTo('portfolio.social.view')) {
            $navigation['social'] = [
                'scope'   => 'social',
                'icon'    => ['fal', 'fa-thumbs-up'],
                'label'   => __('Social'),
                'route'   => 'customer.social.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.social.websites.index',
                            ]
                        ],
                    ],
                ]
            ];
        }


        if ($customerUser->hasPermissionTo('portfolio.banners.view')) {
            $navigation['banners'] = [
                'scope'   => 'banners',
                'icon'    => ['fal', 'fa-rectangle-wide'],
                'label'   => __('Banners'),
                'route'   => 'customer.banners.dashboard',
                'topMenu' => [
                    'subSections' => [

                        [
                            'icon'  => ['fal', 'fa-chart-network'],
                            'route' => [
                                'name' => 'customer.banners.dashboard',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-rectangle-wide'],
                            'label' => __('banners'),
                            'route' => [
                                'name' => 'customer.banners.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.banners.websites.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-photo-video'],
                            'label' => __('gallery'),
                            'route' => [
                                'name' => 'customer.banners.gallery',
                            ]
                        ],

                    ],

                ]


            ];
        }

        if ($customerUser->hasPermissionTo('sysadmin.view')) {
            $navigation['sysadmin'] = [
                'label'   => __('Manage account'),
                'icon'    => ['fal', 'fa-users-cog'],
                'route'   => 'customer.sysadmin.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-chart-network'],
                            'route' => [
                                'name' => 'customer.sysadmin.dashboard',
                            ]
                        ],
                        [
                            'label' => __('users'),
                            'icon'  => ['fal', 'fa-terminal'],
                            'route' => [
                                'name' => 'customer.sysadmin.users.index',

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
