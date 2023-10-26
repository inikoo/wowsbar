<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Task\TaskActivity\UI;

use App\Actions\Helpers\Snapshot\UI\IndexSnapshots;
use App\Actions\InertiaAction;
use App\Actions\UI\WithInertia;
use App\Actions\Web\HasWorkshopAction;
use App\Actions\Web\Webpage\IndexWebpages;
use App\Actions\Web\Website\UI\ShowWebsite;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Enums\UI\Organisation\TaskActivityTabsEnum;
use App\Enums\UI\Organisation\WebpageTabsEnum;
use App\Http\Resources\Portfolio\SnapshotResource;
use App\Http\Resources\Web\WebpageResource;
use App\Models\Task\TaskActivity;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowTaskActivity extends InertiaAction
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('tasks.edit');
        $this->canDelete = $request->user()->hasPermissionTo('tasks.edit');

        return $request->user()->hasPermissionTo("tasks.view");
    }

    public function asController(TaskActivity $taskActivity, ActionRequest $request): TaskActivity
    {
        $this->initialisation($request)->withTab(TaskActivityTabsEnum::values());

        return $taskActivity;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWebsite(TaskActivity $taskActivity, ActionRequest $request): TaskActivity
    {
        $this->initialisation($request)->withTab(TaskActivityTabsEnum::values());

        return $taskActivity;
    }

    public function htmlResponse(TaskActivity $taskActivity, ActionRequest $request): Response
    {
        return Inertia::render(
            'Task/TaskActivity',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->originalParameters()
                ),
                'title'       => __('task activity'),
                'pageHead'    => [
                    'title'   => $taskActivity->task->type->name,
                    'icon'    => [
                        'title' => __('task activity'),
                        'icon'  => 'fal fa-browser'
                    ],
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => TaskActivityTabsEnum::navigation()
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
                            'label' => __('webpages')
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
