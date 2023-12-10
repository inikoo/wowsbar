<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 23:52:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Tasks;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Models\SysAdmin\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowTasksDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("tasks.view");
    }


    public function asController(): Organisation
    {
        return organisation();
    }


    public function htmlResponse(Organisation $organisation): Response
    {
        return Inertia::render(
            'Tasks/TasksDashboard',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(),
                'title'        => __('tasks'),
                'pageHead'     => [
                    'title' => __('tasks'),
                ],
                'flatTreeMaps' => [
                    [
                        [
                            'name'  => __('divisions'),
                            'icon'  => ['fal', 'fa-indent'],
                            'href'  => ['org.labour.divisions.index'],
                            'index' => [
                                'number' => $organisation->taskStats->number_divisions
                            ]
                        ],
                        [
                            'name'  => __('task types'),
                            'icon'  => ['fal', 'fa-tasks-alt'],
                            'href'  => ['org.labour.types.index'],
                            'index' => [
                                'number' => $organisation->taskStats->number_task_types
                            ]
                        ],
                        [
                            'name'  => __('tasks'),
                            'icon'  => ['fal', 'fa-tasks'],
                            'href'  => ['org.labour.tasks.index'],
                            'index' => [
                                'number' => $organisation->taskStats->number_tasks
                            ]
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
                                'name' => 'org.labour.dashboard'
                            ],
                            'label' => __('tasks'),
                        ]
                    ]
                ]
            );
    }
}
