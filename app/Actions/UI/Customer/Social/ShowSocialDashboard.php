<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 09:40:45 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Social;

use App\Actions\Helpers\History\IndexCustomerModuleHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Enums\UI\Customer\PortfolioDashboardTabsEnum;
use App\Enums\UI\Customer\PPCDashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\CRM\Customer;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowSocialDashboard extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.social.view");
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
            'Social/SocialDashboard',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(),
                'title'        => __('Social media'),
                'pageHead'     => [
                    'title'             => __('Social media'),
                    'icon'              => [
                        'icon'    => ['fal', 'fa-thumbs-up'],
                        'tooltip' => __('Social media')
                    ],
                ],

                'tabs'                             => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioDashboardTabsEnum::navigation(),
                ],
                PortfolioDashboardTabsEnum::DASHBOARD->value => $this->tab == PortfolioDashboardTabsEnum::DASHBOARD->value ?
                    fn () => $this->getDashboard($customer)
                    : Inertia::lazy(fn () =>  $this->getDashboard($customer)),

                PPCDashboardTabsEnum::CHANGELOG->value => $this->tab == PPCDashboardTabsEnum::CHANGELOG->value
                    ?
                    fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_social'],
                            prefix: PPCDashboardTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_social'],
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
                        'href'  => ['customer.portfolio.websites.index'],
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
                                'name' => 'customer.social.dashboard'
                            ],
                            'label' => __('Social'),
                        ]
                    ]
                ]
            );
    }


}
