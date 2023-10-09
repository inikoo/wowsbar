<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 14:12:54 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Dashboard;

use App\Actions\Portfolio\Banner\UI\GetLastEditedBanner;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowDashboard
{
    use AsAction;

    public function asController(ActionRequest $request): Response
    {

        $customer=customer();

        $latestBanners=GetLastEditedBanner::run($customer);



        return Inertia::render(
            'Dashboard',
            [
                'title'       => __('dashboard'),
                'breadcrumbs' => $this->getBreadcrumbs(__('dashboard')),
                'banners'     => $latestBanners,
                'banners_count'=>$latestBanners->count(),
                'portfolio_websites_count'=>$customer->portfolioStats->number_portfolio_websites,
                'name'        => $request->user()->contact_name??$request->user()->slug
            ]
        );
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
