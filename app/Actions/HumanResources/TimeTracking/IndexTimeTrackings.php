<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\TimeTracking;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\HumanResources\ShowHumanResourcesDashboard;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Http\Resources\HumanResources\TimeTrackingsResource;
use App\InertiaTable\InertiaTable;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use App\Models\HumanResources\TimeTracking;
use App\Models\SysAdmin\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexTimeTrackings extends InertiaAction
{
    private JobPosition|Organisation $parent;


    public function handle(Organisation|Employee $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent = $parent;
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('time_trackings.contact_name', $value)
                    ->orWhereStartWith('time_trackings.alias', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(TimeTracking::class);


        if (class_basename($parent) == 'Employee') {
            $queryBuilder->where('time_trackings.subject_type', 'Employee')->where('time_trackings.subject_id', $parent->id);
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('time_trackings.slug')
            ->allowedSorts(['slug'])
            ->allowedFilters([$globalSearch, 'slug'])
            ->withPaginator($prefix)
            ->withQueryString();
    }


    public function tableStructure(Organisation|Employee $parent, ?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $parent) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            foreach ($this->elementGroups as $key => $elementGroup) {
                $table->elementGroup(
                    key: $key,
                    label: $elementGroup['label'],
                    elements: $elementGroup['elements']
                );
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('no time sheets'),
                        'count' => organisation()->stats->number_organisation_users_type_employee,
                    ]
                )
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true);

            if (class_basename($parent) == 'Organisation') {
                $table->column(key: 'employee_name', label: __('Employee'), canBeHidden: false);
            }
            $table->defaultSort('slug');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('hr.edit');

        return $request->user()->hasPermissionTo('hr.view');
    }


    public function jsonResponse(LengthAwarePaginator $employees): AnonymousResourceCollection
    {
        return EmployeeResource::collection($employees);
    }

    public function htmlResponse(LengthAwarePaginator $employees): Response
    {
        return Inertia::render(
            'HumanResources/TimeTrackings',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('time sheets'),
                'pageHead'    => [
                    'title' => __('time sheets'),
                    /*
                    'actions' => [
                        $this->canEdit ? [
                            'type'    => 'buttonGroup',
                            'buttons' => [
                                [
                                    'name'  => 'uploadEmployees',
                                    'style' => 'secondary',
                                    'icon'  => ['fal', 'fa-upload'],
                                    'route' => [
                                        'name' => 'org.models.time_trackings.upload'
                                    ],
                                    'method'=> 'post'
                                ],
                                [
                                    'type'  => 'button',
                                    'style' => 'create',
                                    'label' => __('employee'),
                                    'route' => [
                                        'name'       => 'org.hr.time_trackings.create',
                                        'parameters' => array_values($this->originalParameters)
                                    ]
                                ]
                            ]
                        ] : false
                    ]
                ],
                    */
                ],
                'data'        => TimeTrackingsResource::collection($employees),
            ]
        )->table($this->tableStructure($this->parent));
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle(organisation());
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
                            'name' => 'org.hr.time-sheets.index'
                        ],
                        'label' => __('time sheets'),
                        'icon'  => 'fal fa-stopwatch',
                    ],

                ]
            ]
        );
    }
}
