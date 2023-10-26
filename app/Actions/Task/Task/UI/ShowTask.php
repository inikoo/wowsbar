<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Task\Task\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\WithInertia;
use App\Actions\Web\Website\UI\ShowWebsite;
use App\Enums\UI\Organisation\TaskTabsEnum;
use App\Models\Task\Task;
use App\Models\Web\Webpage;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowTask extends InertiaAction
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('tasks.edit');
        $this->canDelete = $request->user()->hasPermissionTo('tasks.edit');

        return $request->user()->hasPermissionTo("tasks.view");
    }

    public function asController(Task $task, ActionRequest $request): Task
    {
        $this->initialisation($request)->withTab(TaskTabsEnum::values());

        return $task;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWebsite(Task $task, ActionRequest $request): Task
    {
        $this->initialisation($request)->withTab(TaskTabsEnum::values());

        return $task;
    }

    public function htmlResponse(Task $task, ActionRequest $request): Response
    {
        return Inertia::render(
            'Task/Task',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->originalParameters()
                ),
                'title'       => __('task activity'),
                'pageHead'    => [
                    'title'   => $task->type->name,
                    'icon'    => [
                        'title' => __('task activity'),
                        'icon'  => 'fal fa-task'
                    ],
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => TaskTabsEnum::navigation()
                ],

            ]
        );
    }

    public function getBreadcrumbs(array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (Webpage $webpage, array $routeParameters, string $suffix) {
            return [
                [

                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('task')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $webpage->code,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };

        return array_merge(
            ShowWebsite::make()->getBreadcrumbs(
                [
                    'website'=> $routeParameters['website']
                ]
            ),
            $headCrumb(
                Webpage::where('slug', $routeParameters['webpage'])->first(),
                [
                    'index' => [
                        'name'       => 'org.websites.show.webpages.index',
                        'parameters' => [
                            'website' => $routeParameters['website']

                        ]
                    ],
                    'model' => [
                        'name'       => 'org.websites.show.webpages.show',
                        'parameters' => [
                            'website' => $routeParameters['website'],
                            'webpage' => $routeParameters['webpage']
                        ]
                    ]
                ],
                $suffix
            ),
        );
    }
}
