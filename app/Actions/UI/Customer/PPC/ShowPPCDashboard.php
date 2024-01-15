<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 09:44:19 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\PPC;

use App\Actions\Helpers\History\IndexCustomerModuleHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Enums\UI\Customer\PPCDashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\CRM\Customer;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowPPCDashboard extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.ppc.view");
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request)->withTab(PPCDashboardTabsEnum::values());

        return $request;
    }


    public function htmlResponse(ActionRequest $request): Response
    {
        $customer = $request->get('customer');

        return Inertia::render(
            'PPC/PPCDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('Google Ads'),
                'pageHead'    => [
                    'title' => __('Google Ads'),
                    'icon'  => [
                        'icon'    => ['fal', 'fa-ad'],
                        'tooltip' => __('Google Ads')
                    ],
                ],

                'tabs'                                 => [
                    'current'    => $this->tab,
                    'navigation' => PPCDashboardTabsEnum::navigation(),
                ],
                PPCDashboardTabsEnum::DASHBOARD->value => $this->tab == PPCDashboardTabsEnum::DASHBOARD->value ?
                    fn () => $this->getDashboard($customer)
                    : Inertia::lazy(fn () => $this->getDashboard($customer)),

                PPCDashboardTabsEnum::CHANGELOG->value => $this->tab == PPCDashboardTabsEnum::CHANGELOG->value
                    ?
                    fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_ppc'],
                            prefix: PPCDashboardTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_ppc'],
                            prefix: PPCDashboardTabsEnum::CHANGELOG->value
                        )
                    ))
            ]
        )->table(
            IndexCustomerModuleHistory::make()->tableStructure(
                prefix: PPCDashboardTabsEnum::CHANGELOG->value
            )
        );
    }

    private function getDashboard(Customer $customer): array
    {
        return [
            'flatTreeMaps' => [
                [
                    [
                        'name'  => __('websites'),
                        'icon'  => ['fal', 'fa-globe'],
                        'href'  => ['name' => 'customer.portfolio.websites.index'],
                        'index' => [
                            'number' => 0 // todo make stats for portfolio division stuff
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
                                'name' => 'customer.ppc.dashboard'
                            ],
                            'label' => __('Google Ads'),
                        ]
                    ]
                ]
            );
    }


}
