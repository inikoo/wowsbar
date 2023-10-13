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
        $number_portfolio_websites = $customerUser->customer->portfolioStats->number_portfolio_websites;

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


        $portfolioSubsections = [
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
                'icon'  => ['fal', 'fa-thumbs-up'],
                'label' => __('social accounts'),
                'route' => [
                    'name' => 'customer.portfolio.social-accounts.index',
                ]
            ],
        ];

        if ($customerUser->hasPermissionTo('portfolio.social.view')) {
            $navigation['social'] = [
                'scope'   => 'social',
                'icon'    => ['fal', 'fa-thumbs-up'],
                'label'   => __('Social accounts'),
                'route'   => 'customer.portfolio.social-accounts.index',
                'topMenu' => [
                    'subSections' => $portfolioSubsections
                ]
            ];
        }


        if ($customerUser->hasPermissionTo('portfolio.view')) {
            $navigation['portfolio'] = [
                'scope'   => 'portfolio',
                'icon'    => ['fal', 'fa-globe'],
                'label'   => __('websites'),
                'route'   => 'customer.portfolio.websites.index',
                'topMenu' => [
                    'subSections' => $portfolioSubsections

                ]


            ];
        }


        if ($customerUser->hasPermissionTo('portfolio.prospects.view') && $number_portfolio_websites > 0) {
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

        if ($customerUser->hasPermissionTo('portfolio.seo.view') && $number_portfolio_websites > 0) {
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

        if ($customerUser->hasPermissionTo('portfolio.ppc.view') && $number_portfolio_websites > 0) {
            $navigation['ppc'] = [
                'scope'   => 'ppc',
                'icon'    => ['fal', 'fa-ad'],
                'label'   => __('Google Ads'),
                'route'   => 'customer.ppc.dashboard',
                'topMenu' => [
                    'subSections' => [
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.ppc.websites.index',
                            ]
                        ],
                    ],
                ]
            ];
        }


        if ($customerUser->hasPermissionTo('portfolio.banners.view') && $number_portfolio_websites > 0) {
            $navigation['caas'] = [
                'scope'   => 'caas',
                'icon'    => ['fal', 'fa-rectangle-wide'],
                'label'   => __('Banners'),
                'route'   => 'customer.caas.banners.index',
                'topMenu' => [
                    'subSections' => [

                        [
                            'icon'  => ['fal', 'fa-chart-network'],
                            'route' => [
                                'name' => 'customer.caas.dashboard',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-rectangle-wide'],
                            'label' => __('banners'),
                            'route' => [
                                'name' => 'customer.caas.banners.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.caas.websites.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-photo-video'],
                            'label' => __('gallery'),
                            'route' => [
                                'name' => 'customer.caas.gallery',
                            ]
                        ],

                    ],

                ]


            ];
        }

        if ($customerUser->hasPermissionTo('sysadmin.view')) {
            $navigation['sysadmin'] = [
                'label'   => __('Account / Users'),
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
