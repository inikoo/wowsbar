<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 08:44:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Catalogue;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCatalogueDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("catalogue.view");
    }


    public function asController(): void
    {
        $this->validateAttributes();
    }


    public function htmlResponse(): Response
    {
        $org = organisation();

        return Inertia::render(
            'Catalogue/CatalogueDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('catalogue'),
                'pageHead'    => [
                    'title' => __('catalogue'),
                ],
                'stats'       => [
                    [
                        'name' => __('departments'),
                        'stat' => $org->catalogueStats->number_departments,
                        'href' => [
                            'name' => 'org.catalogue.departments.index'
                        ]
                    ],
                    [
                        'name' => __('products'),
                        'stat' => $org->catalogueStats->number_products,
                        'href' => [
                            'name' => 'org.catalogue.products.index'
                        ]
                    ],

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
                                'name' => 'org.catalogue.dashboard'
                            ],
                            'label' => __('catalogue'),
                        ]
                    ]
                ]
            );
    }
}
