<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:02:53 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Tenant\Portfolio;

use App\Actions\UI\Tenant\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Tenancy\Tenant;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowPortfolioDashboard
{
    use AsAction;
    use WithInertia;

    public function handle($scope)
    {
        return $scope;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("portfolio.view");
    }


    public function asController(): Tenant
    {
        return app('currentTenant');
    }



    public function htmlResponse(Tenant $tenant): Response
    {

        return Inertia::render(
            'Tenant/Portfolio/PortfolioDashboard',
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
                'flatTreeMaps' => [
                    [

                        [
                            'name'  => __('websites'),
                            'icon'  => ['fal', 'fa-globe'],
                            'href'  => ['portfolio.websites.index'],
                            'index' => [
                                'number' => $tenant->stats->number_websites
                            ]

                        ],



                    ]
]

            ]
        );
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
                                'name' => 'portfolio.dashboard'
                            ],
                            'label' => __('portfolio'),
                        ]
                    ]
                ]
            );
    }


}
