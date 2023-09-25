<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\CustomerWebsites;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCustomerWebsitesDashboard
{
    use AsAction;
    use WithInertia;

    public function handle($parent)
    {
        return $parent;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.view");
    }


    public function asController(): void
    {
        $this->validateAttributes();
    }

    public function inShop(Shop $shop)
    {
        $this->validateAttributes();
        return $this->handle($shop);
    }


    public function htmlResponse(Organisation|Shop $parent, ActionRequest $request): Response
    {

        return Inertia::render(
            'HumanResources/HumanResourcesDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('customer portfolios'),
                'pageHead'    => [
                    'title' => __('customer portfolios'),
                ],
                'stats'       => [
                    [
                        'name' => __("Customer's websites"),
                        'stat' => $parent->crmStats->number_customer_websites,
                        'href' => match (class_basename($parent)) {
                            'Shop'=> [
                                'name'      => 'org.portfolios.shop.customer-websites.index',
                                'parameters'=> $request->route()->originalParameters()
                            ],
                            default=> [
                                'name'=> 'org.portfolios.customer-websites.index'
                            ]
                        }
                    ],

                ]

            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return
            match($routeName) {
                'org.portfolios.show.dashboard'=> array_merge(
                    ShowDashboard::make()->getBreadcrumbs(),
                    [
                        [
                            'type'   => 'simple',
                            'simple' => [
                                'route' => [
                                    'name'      => 'org.portfolios.shop.dashboard',
                                    'parameters'=> $routeParameters
                                ],
                                'label' => __('customer websites'),
                            ]
                        ]
                    ]
                ),
                default=> array_merge(
                    ShowDashboard::make()->getBreadcrumbs(),
                    [
                        [
                            'type'   => 'simple',
                            'simple' => [
                                'route' => [
                                    'name' => 'org.portfolios.dashboard',
                                ],
                                'label' => __('customer websites'),
                            ]
                        ]
                    ]
                )
            };

    }
}
