<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 10:03:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tasks\Division\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Tasks\ShowTasksDashboard;
use App\Http\Resources\Tasks\TaskTypeResource;
use App\InertiaTable\InertiaTable;
use App\Models\Organisation\Division;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexDivisions extends InertiaAction
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

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('divisions.name', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Division::class);


        /** @noinspection PhpUndefinedMethodInspection */
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
            'Tasks/Divisions',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                ),
                'title'       => __('divisions'),
                'pageHead'    => [
                    'title'     => __('divisions'),
                    'iconRight' => [
                        'title' => __('divisions'),
                        'icon'  => 'fal fa-tasks-alt'
                    ],
                ],
                'data'        => TaskTypeResource::collection($taskActivities),
            ]
        )->table($this->tableStructure());
    }

    public function getBreadcrumbs(string $routeName): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('divisions'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.labour.divisions.index' =>
            array_merge(
                ShowTasksDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'org.labour.divisions.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
