<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 09:44:19 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\SEO;

use App\Actions\Helpers\History\IndexCustomerModuleHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Enums\UI\Customer\SEODashboardTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\CRM\Customer;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowSeoDashboard extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.seo.view");
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request)->withTab(SEODashboardTabsEnum::values());

        return $request;
    }


    public function htmlResponse(ActionRequest $request): Response
    {
        $customer = $request->get('customer');

        return Inertia::render(
            'SEO/SEODashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('SEO'),
                'pageHead'    => [
                    'title' => __('SEO'),
                    'icon'  => [
                        'icon'    => ['fab', 'fa-google'],
                        'tooltip' => __('SEO')
                    ],
                ],

                'tabs'                                 => [
                    'current'    => $this->tab,
                    'navigation' => SEODashboardTabsEnum::navigation(),
                ],
                SEODashboardTabsEnum::DASHBOARD->value => $this->tab == SEODashboardTabsEnum::DASHBOARD->value ?
                    fn () => $this->getDashboard($customer)
                    : Inertia::lazy(fn () => $this->getDashboard($customer)),

                SEODashboardTabsEnum::CHANGELOG->value => $this->tab == SEODashboardTabsEnum::CHANGELOG->value
                    ?
                    fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_seo'],
                            prefix: SEODashboardTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => HistoryResource::collection(
                        IndexCustomerModuleHistory::run(
                            customer: $customer,
                            tags: ['customer_seo'],
                            prefix: SEODashboardTabsEnum::CHANGELOG->value
                        )
                    ))

            ]
        )->table(
            IndexCustomerModuleHistory::make()->tableStructure(
                prefix: SEODashboardTabsEnum::CHANGELOG->value
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
                                'name' => 'customer.seo.dashboard'
                            ],
                            'label' => __('SEO'),
                        ]
                    ]
                ]
            );
    }


}
