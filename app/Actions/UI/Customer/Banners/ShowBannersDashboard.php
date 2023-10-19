<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 03:09:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Banners;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Enums\UI\Customer\BannersDashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBannersDashboard extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.banners.view");
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request)->withTab(BannersDashboardTabsEnum::values());
        return $request;
    }



    public function htmlResponse(ActionRequest $request): Response
    {


        return Inertia::render(
            'Banners/BannersDashboard',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(),
                'title'        => __('banners'),
                'pageHead'     => [
                    'title'             => __('banners'),
                    'icon'              => [
                        'icon'    => ['fal', 'fa-sign'],
                        'tooltip' => __('Banners')
                    ],
                ],

                'tabs'                             => [
                    'current'    => $this->tab,
                    'navigation' => BannersDashboardTabsEnum::navigation(),
                ],
                BannersDashboardTabsEnum::DASHBOARD->value => $this->tab == BannersDashboardTabsEnum::DASHBOARD->value ?
                    fn () => $this->getDashboard()
                    : Inertia::lazy(fn () =>  $this->getDashboard()),

                BannersDashboardTabsEnum::PORTFOLIO_CHANGELOG->value => $this->tab == BannersDashboardTabsEnum::PORTFOLIO_CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(PortfolioWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(PortfolioWebsite::class)))

            ]
        )->table(IndexHistory::make()->tableStructure());
    }

    private function getDashboard(): array
    {
        $customer=customer();

        return [
            'flatTreeMaps' => [
                [
                    [
                        'name'  => __('banners'),
                        'icon'  => ['fal', 'fa-sign'],
                        'href'  => ['customer.banners.banners.index'],
                        'index' => [
                            'number' => $customer->portfolioStats->number_banners
                        ]
                    ],
                ]
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'customer.banners.dashboard'
                            ],
                            'label' => __('Banners dashboard'),
                        ]
                    ]
                ]
            );
    }


}
