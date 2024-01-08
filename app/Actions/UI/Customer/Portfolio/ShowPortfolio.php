<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 31 Aug 2023 10:07:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Portfolio;

use App\Actions\Helpers\History\IndexCustomerModuleHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Enums\UI\Customer\PortfolioDashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowPortfolio extends InertiaAction
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
        $customer = $request->get('customer');

        return Inertia::render(
            'Portfolio/Portfolio',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('portfolio'),
                'pageHead'    => [
                    'title' => __('portfolio'),
                    'icon'  => [
                        'icon'    => ['fal', 'fa-suitcase'],
                        'tooltip' => __('portfolio')
                    ],
                ],

                'tabs'                                       => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioDashboardTabsEnum::navigation(),
                ],
                PortfolioDashboardTabsEnum::DASHBOARD->value => $this->tab == PortfolioDashboardTabsEnum::DASHBOARD->value ?
                    fn () => $this->getDashboard()
                    : Inertia::lazy(fn () => $this->getDashboard()),

                PortfolioDashboardTabsEnum::CHANGELOG->value => $this->tab == PortfolioDashboardTabsEnum::CHANGELOG->value
                    ?
                    fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['portfolio'],
                            prefix: PortfolioDashboardTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['portfolio'],
                            prefix: PortfolioDashboardTabsEnum::CHANGELOG->value
                        )
                    ))

            ]
        )
            ->table(IndexCustomerModuleHistory::make()->tableStructure(prefix: PortfolioDashboardTabsEnum::CHANGELOG->value));
    }

    private function getDashboard(): array
    {
        $customer = customer();


        return [
            'flatTreeMaps' => [
                [
                    [
                        'name'  => __('websites'),
                        'icon'  => ['fal', 'fa-globe'],
                        'href'  => ['name' => 'customer.portfolio.websites.index'],
                        'index' => [
                            'number' => $customer->portfolioStats->number_portfolio_websites
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
                                'name' => 'customer.portfolio.dashboard'
                            ],
                            'label' => __('portfolio'),
                        ]
                    ]
                ]
            );
    }


}
