<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:19:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tasks\TaskType\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Tasks\ShowTasksDashboard;
use App\Http\Resources\Tasks\TaskTypeResource;
use App\InertiaTable\InertiaTable;
use App\Models\Tasks\TaskType;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexTaskTypes extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('tasks.edit');

        return $request->user()->hasPermissionTo('tasks.view');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('task_types.name', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(TaskType::class);


        return $queryBuilder
            ->defaultSort('name')
            ->allowedSorts(['name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withExportLinks($exportLinks)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->defaultSort('name');
        };
    }


    public function htmlResponse(LengthAwarePaginator $taskActivities, ActionRequest $request): Response
    {
        return Inertia::render(
            'Tasks/TaskTypes',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('task types'),
                'pageHead'    => [
                    'title'     => __('task types'),
                    'iconRight' => [
                        'title' => __('task types'),
                        'icon'  => 'fal fa-tasks-alt'
                    ],
                ],
                'data'        => TaskTypeResource::collection($taskActivities),
            ]
        )->table($this->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('task types'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.labour.types.index' =>
            array_merge(
                ShowTasksDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'org.labour.types.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
