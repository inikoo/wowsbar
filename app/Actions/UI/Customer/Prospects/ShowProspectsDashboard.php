<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 09:00:40 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Prospects;

use App\Actions\Helpers\History\IndexCustomerModuleHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Enums\UI\Customer\ProspectsDashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\CRM\Customer;
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
        $this->initialisation($request)->withTab(ProspectsDashboardTabsEnum::values());

        return $request;
    }


    public function htmlResponse(ActionRequest $request): Response
    {
        $customer = $request->get('customer');

        return Inertia::render(
            'Prospects/ProspectsDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('Leads'),
                'pageHead'    => [
                    'title' => __('leads'),
                    'icon'  => [
                        'icon'    => ['fal', 'fa-transporter'],
                        'tooltip' => __('leads')
                    ],
                ],

                'tabs'                                       => [
                    'current'    => $this->tab,
                    'navigation' => ProspectsDashboardTabsEnum::navigation(),
                ],
                ProspectsDashboardTabsEnum::DASHBOARD->value => $this->tab == ProspectsDashboardTabsEnum::DASHBOARD->value ?
                    fn () => $this->getDashboard($customer)
                    : Inertia::lazy(fn () => $this->getDashboard($customer)),


                ProspectsDashboardTabsEnum::CHANGELOG->value => $this->tab == ProspectsDashboardTabsEnum::CHANGELOG->value
                    ?
                    fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_prospects'],
                            prefix: ProspectsDashboardTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_prospects'],
                            prefix: ProspectsDashboardTabsEnum::CHANGELOG->value
                        )
                    ))


            ]
        )->table(
            IndexCustomerModuleHistory::make()->tableStructure(
                prefix: ProspectsDashboardTabsEnum::CHANGELOG->value
            )
        );
    }

    private function getDashboard(Customer $customer): array
    {
        return [
            'flatTreeMaps' => [
                [
                    [
                        'name'  => __('prospects'),
                        'icon'  => ['fal', 'fa-transporter-2'],
                        'href'  => ['customer.portfolio.websites.index'],
                        'index' => [
                            'number' => $customer->portfolioStats->number_prospects
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
