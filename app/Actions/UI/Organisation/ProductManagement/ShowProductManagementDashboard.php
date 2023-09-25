<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 08:44:53 Malaysia Time, Kuala Lumpur, Malysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\ProductManagement;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowProductManagementDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.view");
    }


    public function asController(): void
    {
        $this->validateAttributes();
    }


    public function htmlResponse(): Response
    {
        $org = organisation();

        return Inertia::render(
            'ProductManagement/ProductManagementDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('product management'),
                'pageHead'    => [
                    'title' => __('product management'),
                ],
                'stats'       => [
                    [
                        'name' => __('products'),
                        'stat' => $org->humanResourcesStats->number_employees,
                        'href' => ['name'=>'org.hr.employees.index']
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
                                'name' => 'org.hr.dashboard'
                            ],
                            'label' => __('human resources'),
                        ]
                    ]
                ]
            );
    }
}
