<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\HumanResources\ShowHumanResourcesDashboard;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Enums\HumanResources\Employee\EmployeeTypeEnum;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Http\Resources\HumanResources\EmployeesResource;
use App\InertiaTable\InertiaTable;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use App\Models\SysAdmin\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexEmployees extends InertiaAction
{
    private JobPosition|Organisation $parent;

    protected function getElementGroups(Organisation|JobPosition $parent): array
    {
        return [
            'state' => [
                'label'    => __('State'),
                'elements' => array_merge_recursive(
                    EmployeeStateEnum::labels(),
                    EmployeeStateEnum::count($parent)
                ),

                'engine' => function ($query, $elements) {
                    $query->whereIn('state', $elements);
                }

            ],
            'type'  => [
                'label'    => __('Type'),
                'elements' => EmployeeTypeEnum::labels(),
                'engine'   => function ($query, $elements) {
                    $query->whereIn('type', $elements);
                }
            ],
        ];
    }


    public function handle(Organisation|JobPosition $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent = $parent;
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('employees.contact_name', $value)
                    ->orWhereStartWith('employees.alias', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(Employee::class);
        foreach ($this->getElementGroups($parent) as $key => $elementGroup) {
            /** @noinspection PhpUndefinedMethodInspection */
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        $queryBuilder->select(['slug', 'job_title', 'contact_name', 'state']);

        if (class_basename($parent) == 'Organisation') {
            $jobPositions = DB::table('job_positionables')
                ->select(
                    'job_positionable_id',
                    DB::raw('jsonb_agg(json_build_object(\'name\',job_positions.name,\'slug\',job_positions.slug)) as job_positions')
                )
                ->leftJoin('job_positions', 'job_positionables.job_position_id', 'job_positions.id')
                ->where('job_positionable_type', 'Employee')
                ->groupBy('job_positionable_id');
            $queryBuilder->leftJoinSub($jobPositions, 'job_positions', function (JoinClause $join) {
                $join->on('employees.id', '=', 'job_positions.job_positionable_id');
            });
            $queryBuilder->addSelect('job_positions');
        } else {
            $queryBuilder->leftJoin('job_positionables', 'job_positionables.job_positionable_id', 'employees.id')
                ->where('job_positionables.job_positionable_type', 'Employee')
                ->where('job_position_id', $parent->id);
        }


        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('employees.slug')
            ->allowedSorts(['slug', 'state', 'contact_name', 'job_title', 'worker_number'])
            ->allowedFilters([$globalSearch, 'slug', 'contact_name', 'state'])
            ->withPaginator($prefix)
            ->withQueryString();
    }


    public function tableStructure(Organisation|JobPosition $parent, ?array $modelOperations = null, $prefix = null): Closure
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
                        'title'       => __('no employees'),
                        'description' => $this->canEdit ? __('Get started by creating a new employee.') : null,
                        'count'       => organisation()->stats->number_organisation_users_type_employee,
                        'action'      => $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new employee'),
                            'label'   => __('employee'),
                            'route'   => [
                                'name'       => 'org.hr.employees.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : null
                    ]
                )
                ->column(key: 'state', label: ['fal', 'fa-yin-yang'], type: 'icon')
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'contact_name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'job_title', label: __('job title'), canBeHidden: false);

            if (class_basename($parent) == 'Organisation') {
                $table->column(key: 'positions', label: __('positions'), canBeHidden: false);
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
            'HumanResources/Employees',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('employees'),
                'pageHead'    => [
                    'title'   => __('employees'),
                    'actions' => [
                        $this->canEdit ? [
                            'type'    => 'buttonGroup',
                            'buttons' => [
                                [
                                    'name'  => 'uploadEmployees',
                                    'style' => 'secondary',
                                    'icon'  => ['fal', 'fa-upload'],
                                    'route' => [
                                        'name' => 'org.models.employees.upload'
                                    ],
                                    'method'=> 'post'
                                ],
                                [
                                    'type'  => 'button',
                                    'style' => 'create',
                                    'label' => __('employee'),
                                    'route' => [
                                        'name'       => 'org.hr.employees.create',
                                        'parameters' => array_values($this->originalParameters)
                                    ]
                                ]
                            ]
                        ] : false
                    ]
                ],
                'uploads' => [
                    'templates' => [
                        'routes' => [
                            'name' => 'org.downloads.templates.employees'
                        ]
                    ],
                    'event'   => class_basename(Employee::class),
                    'channel' => 'uploads.org.' . request()->user()->id
                ],
                'data'        => EmployeesResource::collection($employees),
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
                            'name' => 'org.hr.employees.index'
                        ],
                        'label' => __('employees'),
                        'icon'  => 'fal fa-bars',
                    ],

                ]
            ]
        );
    }
}
