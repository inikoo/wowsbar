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

        // $navigation['portfolio'] = [
        //     'scope'   => 'portfolio',
        //     'icon'    => ['fal', 'fa-briefcase'],
        //     'label'   => __('Portfolio'),
        //     'route'   => 'customer.portfolio.dashboard',
        //     'topMenu' => [
        //         'subSections' => $portfolioSubsections
        //     ]
        // ];

        // Nav: Social Accounts
        if ($customerUser->hasPermissionTo('portfolio.social.view')) {
            $navigation['social'] = [
                'scope'   => 'portfolio',
                'icon'    => ['fal', 'fa-thumbs-up'],
                'label'   => __('Social accounts'),
                'route'   => 'customer.portfolio.social-accounts.index',
                'topMenu' => [
                    'subSections' => $portfolioSubsections
                ]
            ];
        }


        $websiteSubNav = [];

        if ($customerUser->hasPermissionTo('portfolio.prospects.view') && $number_portfolio_websites > 0) {
            $websiteSubNav['prospects'] = [
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
            $websiteSubNav['seo'] = [
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
            $websiteSubNav['ppc'] = [
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
            $websiteSubNav['banners'] = [
                'scope'   => 'banners',
                'icon'    => ['fal', 'fa-sign'],
                'label'   => __('Banners'),
                'route'   => 'customer.banners.banners.index',
                'topMenu' => [
                    'subSections' => [

                        [
                            'icon'  => ['fal', 'fa-chart-network'],
                            'route' => [
                                'name' => 'customer.banners.dashboard',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-sign'],
                            'label' => __('banners'),
                            'route' => [
                                'name' => 'customer.banners.banners.index',
                            ]
                        ],
                        [
                            'icon'  => ['fal', 'fa-globe'],
                            'label' => __('websites'),
                            'route' => [
                                'name' => 'customer.banners.websites.index',
                            ]
                        ],/*
                        [
                            'icon'  => ['fal', 'fa-photo-video'],
                            'label' => __('gallery'),
                            'route' => [
                                'name' => 'customer.banners.gallery',
                            ]
                        ],
                        */
                    ],

                ]


            ];
        }

        // Websites
        if ($customerUser->hasPermissionTo('portfolio.view')) {
            $navigation['websites'] = [
                'scope'   => 'portfolio',
                'icon'    => ['fal', 'fa-globe'],
                'label'   => __('websites'),
                'route'   => $customerUser->hasPermissionTo('portfolio') ? 'customer.portfolio.websites.index' : null,
                'topMenu' => [
                    'subSections' => $portfolioSubsections
                ],
                'subNav'  => $websiteSubNav,
            ];
        }

        if ($customerUser->hasPermissionTo('billing.view') and app()->environment('local')) {
            $navigation['billing'] = [
                'scope'   => 'billing',
                'icon'    => ['fal', 'fa-credit-card'],
                'label'   => __('billing'),
                'route'   => 'customer.billing.dashboard',
                'topMenu' => [
                    'subSections' => []
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
