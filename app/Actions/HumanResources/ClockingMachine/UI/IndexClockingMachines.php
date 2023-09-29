<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\HumanResources\HumanResourcesDashboard;
use App\Http\Resources\HumanResources\ClockingMachineResource;
use App\InertiaTable\InertiaTable;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use App\Models\Tenancy\Tenant;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexClockingMachines extends InertiaAction
{
    public function handle(Workplace|Tenant $parent, $prefix=null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('clocking_machines.slug', 'ILIKE', "%$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        /**  @noinspection PhpUndefinedMethodInspection */
        return QueryBuilder::for(ClockingMachine::class)
            ->defaultSort('clocking_machines.code')
            ->select(
                [
                    'clocking_machines.code as code',
                    'clocking_machines.id',
                    'workplaces.slug as workplace_slug',
                    'clocking_machines.slug'
                ]
            )
            ->leftJoin('workplaces', 'clocking_machines.workplace_id', 'workplaces.id')
            ->when($parent, function ($query) use ($parent) {
                if (class_basename($parent) == 'Workplace') {
                    $query->where('clocking_machines.workplace_id', $parent->id);
                }
            })
            ->allowedSorts(['slug','code'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
            if($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }
            $table
                ->withGlobalSearch()
                ->withModelOperations($modelOperations)
                ->withEmptyState(
                    [
                        'title'       => __('no clocking machines'),
                        'description' => $this->canEdit ? __('Get started by creating a new clocking machine.') : null,
                        'count'       => organisation()->stats->number_clocking_machines,
                        'action'      => $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new clocking machine'),
                            'label'   => __('clocking machine'),
                            'route'   => [
                                'name'       => 'hr.clocking-machines.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : null
                    ]
                )
                ->column(key: 'code', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('code');
        };
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()>hasPermissionTo('hr.workplaces.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('hr.view')
            );
    }


    public function inOrganisation(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle(organisation());
    }

    public function inWorkplace(Workplace $workplace, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle($workplace);
    }


    public function jsonResponse(LengthAwarePaginator $clockingMachine): AnonymousResourceCollection
    {
        return ClockingMachineResource::collection($clockingMachine);
    }


    public function htmlResponse(LengthAwarePaginator $clockingMachines, ActionRequest $request): Response
    {
        return Inertia::render(
            'HumanResources/ClockingMachines',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('clocking machines'),
                'pageHead'    => [
                    'title'  => __('clocking machines'),
                    'actions'=> [
                        $this->canEdit && $request->route()->getName() == 'org.hr.workplaces.show.clocking-machines.index' ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('clocking machine'),
                            'route' => [
                                'name'       => 'org.hr.workplaces.show.clocking-machines.create',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ] : false
                    ]
                ],
                'data'        => ClockingMachineResource::collection($clockingMachines)

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
                        'label' => __('clocking machines'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'hr.clocking-machines.index' =>
            array_merge(
                (new HumanResourcesDashboard())->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'hr.clocking-machines.index',
                        null
                    ]
                )
            ),
            'org.hr.workplaces.show.clocking-machines.index',
            =>
            array_merge(
                (new \App\Actions\HumanResources\Workplace\UI\ShowWorkplace())->getBreadcrumbs(
                    $routeParameters['workplace']
                ),
                $headCrumb([
                    'name'       => 'org.hr.workplaces.show.clocking-machines.index',
                    'parameters' =>
                        [
                            $routeParameters['workplace']->slug
                        ]
                ])
            ),
            default => []
        };
    }
}
