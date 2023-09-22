<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\HumanResources\ShowHumanResourcesDashboard;
use App\Enums\UI\Organisation\EmployeeTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowEmployee extends InertiaAction
{
    public function handle(Employee $employee): Employee
    {
        return $employee;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('hr.edit');
        $this->canDelete = $request->user()->can('hr.edit');

        return $request->user()->hasPermissionTo("hr.view");
    }

    public function asController(Employee $employee, ActionRequest $request): Employee
    {
        $this->initialisation($request)->withTab(EmployeeTabsEnum::values());

        return $this->handle($employee);
    }

    public function htmlResponse(Employee $employee, ActionRequest $request): Response
    {
        $meta = [];
        if ($employee->worker_number) {
            $meta[] = [
                'label'    => $employee->worker_number,
                'leftIcon' => [
                    'icon'    => 'fal fa-id-card',
                    'tooltip' => __('Worker number')
                ]
            ];
        }
        if ($employee->organisationUser) {
            $meta[] = [
                'label'    => $employee->organisationUser->username,
                'leftIcon' => [
                    'icon'    => 'fal fa-user',
                    'tooltip' => __('User')
                ]
            ];
        } else {
            $meta[] = [
                'label'    => __('New user'),
                'type'     => 'button',
                'leftIcon' => [
                    'icon'    => 'fal fa-user-slash',
                    'tooltip' => __('Give employee access to system')
                ]
            ];
        }

        return Inertia::render(
            'HumanResources/Employee',
            [
                'title'       => __('employee'),
                'breadcrumbs' => $this->getBreadcrumbs($employee),
                'navigation'  => [
                    'previous' => $this->getPrevious($employee, $request),
                    'next'     => $this->getNext($employee, $request),
                ],
                'pageHead'    => [
                    'title'   => $employee->contact_name,
                    'meta'    => $meta,
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => $request->route()->originalParameters()
                            ]
                        ] : false,
                        $this->canDelete ? [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => 'org.hr.employees.remove',
                                'parameters' => $request->route()->originalParameters()
                            ]

                        ] : false
                    ]
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => EmployeeTabsEnum::navigation()
                ],

                EmployeeTabsEnum::DATA->value => $this->tab == EmployeeTabsEnum::DATA->value ?
                    fn() => $this->getData($employee)
                    : Inertia::lazy(fn() => $this->getData($employee)),

                EmployeeTabsEnum::HISTORY->value => $this->tab == EmployeeTabsEnum::HISTORY->value ?
                    fn() => HistoryResource::collection(IndexHistories::run($employee))
                    : Inertia::lazy(fn() => HistoryResource::collection(IndexHistories::run($employee)))
            ]
        )->table(IndexHistories::make()->tableStructure());
    }

    public function getData(Employee $employee): array
    {
        return Arr::except($employee->toArray(), ['id', 'source_id', 'working_hours', 'errors', 'salary', 'data', 'job_position_scopes']);
    }

    public function jsonResponse(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    public function getBreadcrumbs(Employee $employee, $suffix = null): array
    {
        return array_merge(
            (new ShowHumanResourcesDashboard())->getBreadcrumbs(),
            [
                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => [
                                'name' => 'org.hr.employees.index',
                            ],
                            'label' => __('employees')
                        ],
                        'model' => [
                            'route' => [
                                'name'       => 'org.hr.employees.show',
                                'parameters' => [$employee->slug]
                            ],
                            'label' => $employee->slug,
                        ],
                    ],
                    'suffix'         => $suffix,

                ],
            ]
        );
    }

    public function getPrevious(Employee $employee, ActionRequest $request): ?array
    {
        $previous = Employee::where('slug', '<', $employee->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Employee $employee, ActionRequest $request): ?array
    {
        $next = Employee::where('slug', '>', $employee->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Employee $employee, string $routeName): ?array
    {
        if (!$employee) {
            return null;
        }

        return match ($routeName) {
            'org.hr.employees.show' => [
                'label' => $employee->contact_name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'employee' => $employee->slug
                    ]

                ]
            ]
        };
    }
}
