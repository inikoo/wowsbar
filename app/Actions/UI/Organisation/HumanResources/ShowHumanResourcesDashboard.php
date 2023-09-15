<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Mar 2023 18:46:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\HumanResources;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowHumanResourcesDashboard
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
            'HumanResources/HumanResourcesDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('human resources'),
                'pageHead'    => [
                    'title' => __('human resources'),
                ],
                'stats'       => [
                    [
                        'name' => __('employees'),
                        'stat' => $org->humanResourcesStats->number_employees,
                        'href' => ['org.hr.employees.index']
                    ],
                    [
                        'name' => __('working places'),
                        'stat' => $org->humanResourcesStats->number_working_places,
                        'href' => ['org.hr.working-places.index']
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
                                'name' => 'org.hr.dashboard'
                            ],
                            'label' => __('human resources'),
                        ]
                    ]
                ]
            );
    }
}
