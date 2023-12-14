<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:49:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\UI\CRM;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCRMDashboard
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.view");
    }


    public function asController(ActionRequest $request): Organisation
    {
        $request->validate();

        return organisation();
    }

    public function inShop(Shop $shop, ActionRequest $request): Shop
    {
        $request->validate();

        return $shop;
    }

    public function htmlResponse(Organisation|Shop $parent, ActionRequest $request): Response
    {
        $routeName       = $request->route()->getName();
        $routeParameters = $request->route()->originalParameters();

        return Inertia::render(
            'CRM/CRMDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $routeName,
                    $routeParameters
                ),
                'title'       => 'CRM',
                'pageHead'    => [
                    'title' => __('customer relationship manager'),
                    'icon'  => [
                        'title' => __('customers'),
                        'icon'  => 'fal fa-user'
                    ],
                ],
                'stats'       => [
                    'customers' => [
                        'name' => __('customers'),
                        'stat' => $parent->crmStats->number_customers,

                        'href' => match ($routeName) {
                            'org.crm.shop.dashboard' =>
                            [
                                'name'      => 'org.crm.shop.customers.index',
                                'parameters'=> $routeParameters
                            ],
                            default => [
                                'name' => 'org.crm.customers.index'
                            ]
                        }

                    ],
                    'prospects' => [
                        'name' => __('prospects'),
                        'stat' => $parent->crmStats->number_prospects,
                        'href' => match ($routeName) {
                            'org.crm.shop.dashboard' =>
                            [
                                'name'      => 'org.crm.shop.prospects.index',
                                'parameters'=> array_merge($routeParameters, [
                                    '_query'=> [
                                        'tab'=> 'prospects'
                                    ]
                                ])
                            ],
                            default => [
                                'name' => 'org.crm.prospects.index'
                            ]
                        }
                    ]
                ]
            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters=[]): array
    {
        return array_merge(
            ShowDashboard::make()->getBreadcrumbs(),
            [
                match ($routeName) {
                    'org.crm.shop.dashboard' => [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name'       => 'org.crm.shop.dashboard',
                                'parameters' => $routeParameters
                            ],
                            'label' => __('CRM'),
                            'icon'  => 'fal fa-chart-network'
                        ]
                    ],
                    default => [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'org.crm.dashboard'
                            ],
                            'label' => __('CRM'),
                        ]
                    ]
                }
            ]
        );
    }

}
