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
                'currentStep' => $welcomeStep,
                'steps'       => [
                    [
                        'storePortfolioWebsiteRoute' => [
                            'name' => 'customer.models.portfolio-website.store.from-welcome'
                        ]
                    ],
                    [

                    ],
                    [

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
