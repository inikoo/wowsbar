<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 14:12:54 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Dashboard;

use App\Actions\Portfolio\Banner\UI\GetLastEditedBanner;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowDashboard
{
    use AsAction;

    public function asController(ActionRequest $request): Response
    {
        $customer = customer();

        $latestBanners = GetLastEditedBanner::run($customer);


        $data = [
            'title'                    => __('dashboard'),
            'breadcrumbs'              => $this->getBreadcrumbs(__('dashboard')),
            'latest_banners'           => $latestBanners,
            'latest_banners_count'     => $latestBanners->count(),
            'portfolio_websites_count' => $customer->portfolioStats->number_portfolio_websites,
            'name'                     => $request->user()->contact_name ?? $request->user()->slug,

        ];

        $welcomeStep = Arr::get($customer->data, 'welcome_step');
        if (in_array($welcomeStep, [1, 2, 3])) {
            $data['welcome'] = [
                'currentStep'   => $welcomeStep,
                'steps_data'    => [
                    'step_1'    => [
                        'id'                         => 1,
                        'label'                      => 'Enter your website name',
                        'description'                => 'Lorem ipsum dolor sit amet.',
                        'component'                  => 1,
                        'websiteValue'               => '',
                        'storePortfolioWebsiteRoute' => [
                            'name'      => 'customer.models.portfolio-website.store.from-welcome'
                        ],
                    ],
                    'step_2'    => [
                        'id'                => 2,
                        'label'             => 'Select your interest',
                        'description'       => 'Lorem ipsum dolor sit amet.',
                        'component'         => 2,
                        "slug"              => "mw",
                        "customer_name"     => "Aiku",
                        "code"              => null,
                        "name"              => "My website 😸",
                        "url"               => "hello.com",
                        "number_banners"    => 0,
                        "seo"               => [
                            "name"          => "seo",
                            "label"         => "SEO",
                            "value"         => 'not_sure'
                        ],
                        "google-ads"    => [
                            "name"          => "google-ads",
                            "label"         => "PPC",
                            "value"         => "not_interested"
                        ],
                        "social"        => [
                            "name"          => "social",
                            "label"         => "Social",
                            "value"         => 'not_sure'
                        ],
                        "prospects"     => [
                            "name"          => "prospects",
                            "label"         => "Prospects",
                            "value"         => 'not_sure'
                        ],
                        "banners"      => [
                            "name"          => "banners",
                            "label"         => "Banners",
                            "value"         => 'not_sure'
                        ]
                    ],
                    'step_3'    => [
                        'id'            => 3,
                        'label'         => 'Make appointment',
                        'description'   => 'Lorem ipsum dolor sit amet.',
                        'component'     => 3,
                        'textareaValue' => '',
                        'inputValue'    => ''
                    ]
                ],


            ];
        }


        return Inertia::render('Dashboard', $data);
    }

    public function getBreadcrumbs($label = null): array
    {
        return [
            [

                'type'   => 'simple',
                'simple' => [
                    'icon'  => 'fal fa-home',
                    'label' => $label,
                    'route' => [
                        'name' => 'customer.dashboard.show'
                    ]
                ]

            ],

        ];
    }
}
