<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 31 Aug 2023 10:07:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Tenant\Portfolio;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Tenant\Dashboard\ShowDashboard;
use App\Enums\UI\Tenant\PortfolioDashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowPortfolio extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.view");
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request)->withTab(PortfolioDashboardTabsEnum::values());
        return $request;
    }



    public function htmlResponse(ActionRequest $request): Response
    {


        return Inertia::render(
            'Portfolio/Portfolio',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(),
                'title'        => __('portfolio'),
                'pageHead'     => [
                    'title'             => __('portfolio'),
                    'icon'              => [
                        'icon'    => ['fal', 'fa-suitcase'],
                        'tooltip' => __('portfolio')
                    ],
                ],

                'tabs'                             => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioDashboardTabsEnum::navigation(),
                ],
                PortfolioDashboardTabsEnum::DASHBOARD->value => $this->tab == PortfolioDashboardTabsEnum::DASHBOARD->value ?
                    fn () => $this->getDashboard()
                    : Inertia::lazy(fn () =>  $this->getDashboard()),

                PortfolioDashboardTabsEnum::PORTFOLIO_CHANGELOG->value => $this->tab == PortfolioDashboardTabsEnum::PORTFOLIO_CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class)))

            ]
        )->table(IndexHistories::make()->tableStructure());
    }

    private function getDashboard(): array
    {
        $tenant=app('currentTenant');

        return [
            'flatTreeMaps' => [
                [
                    [
                        'name'  => __('websites'),
                        'icon'  => ['fal', 'fa-globe'],
                        'href'  => ['tenant.portfolio.websites.index'],
                        'index' => [
                            'number' => $tenant->stats->number_websites
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
                                'name' => 'tenant.portfolio.dashboard'
                            ],
                            'label' => __('portfolio'),
                        ]
                    ]
                ]
            );
    }


}
