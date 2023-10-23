<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\JobPosition\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\HumanResources\ShowHumanResourcesDashboard;
use App\Http\Resources\HumanResources\JobPositionResource;
use App\InertiaTable\InertiaTable;
use App\Models\HumanResources\JobPosition;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexJobPositions extends InertiaAction
{
    private false $canCreate;

    public function handle(string $prefix = null): LengthAwarePaginator
    {
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('job_positions.name', $value)
                    ->orWhere('job_positions.slug', 'ILIKE', "$value%");
            });
        });

        $queryBuilder=QueryBuilder::for(JobPosition::class);
        foreach ($this->elementGroups as $key => $elementGroup) {
            /** @noinspection PhpUndefinedMethodInspection */
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('job_positions.slug')
            ->select(['slug', 'id', 'name', 'number_employees'])
            ->allowedSorts(['slug', 'name', 'number_employees'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit  = false;//$request->user()->hasPermissionTo('hr.edit');
        $this->canCreate=false;
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('hr.view')
            );
    }


    public function jsonResponse(): AnonymousResourceCollection
    {
        return JobPositionResource::collection($this->handle());
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }


            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title'       => __('no job positions'),
                        'description' => $this->canCreate ? __('Get started by creating a new job position.') : null,
                        'count'       => organisation()->humanResourcesStats->number_job_positions,
                        'action'      => $this->canCreate ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new job position'),
                            'label'   => __('job position'),
                            'route'   => [
                                'name'       => 'org.hr.job-positions.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : null
                    ]
                )
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'number_employees', label: __('employees'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('slug');
        };
    }

    public function htmlResponse(LengthAwarePaginator $jobPositions): Response
    {
        return Inertia::render(
            'HumanResources/JobPositions',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('job positions'),
                'pageHead'    => [
                    'title'  => __('positions'),
                    'actions'=> [
                        $this->canCreate ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('job position'),
                            'route' => [
                                'name'       => 'org.hr.job-positions.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : false
                    ]
                ],
                'data'        => JobPositionResource::collection($jobPositions),


            ]
        )->table($this->tableStructure());
    }


    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->perPage = 100;

        return $this->handle();
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            (new ShowHumanResourcesDashboard())->getBreadcrumbs(),
            [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => [
                            'name' => 'org.hr.job-positions.index'
                        ],
                        'label' => __('positions'),
                        'icon'  => 'fal fa-bars',
                    ],

                ]
            ]
        );
    }
}
