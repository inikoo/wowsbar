<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 09:00:40 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Prospects;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Enums\UI\Customer\PortfolioDashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowProspectsDashboard extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.view");
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request)->withTab(PortfolioDashboardTabsEnum::values());
        return $request;
    }



    public function htmlResponse(ActionRequest $request): Response
    {


        return Inertia::render(
            'Prospects/ProspectsDashboard',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(),
                'title'        => __('Leads'),
                'pageHead'     => [
                    'title'             => __('leads'),
                    'icon'              => [
                        'icon'    => ['fal', 'fa-transporter'],
                        'tooltip' => __('leads')
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
        $tenant=customer();

        return [
            'flatTreeMaps' => [
                [
                    [
                        'name'  => __('prospects'),
                        'icon'  => ['fal', 'fa-transporter-2'],
                        'href'  => ['customer.portfolio.websites.index'],
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
                                'name' => 'customer.prospects.dashboard'
                            ],
                            'label' => __('leads'),
                        ]
                    ]
                ]
            );
    }


}
