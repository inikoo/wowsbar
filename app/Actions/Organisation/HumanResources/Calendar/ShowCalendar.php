<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Mar 2023 19:13:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\HumanResources\Calendar;

use App\Actions\InertiaAction;
use App\Actions\UI\HumanResources\HumanResourcesDashboard;
use App\Enums\UI\EmployeeTabsEnum;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\HumanResources\Employee;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowCalendar extends InertiaAction
{
    public function handle(Employee $employee): Employee
    {
        return $employee;
    }


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('hr.edit');

        return $request->user()->hasPermissionTo("hr.view");
    }

    public function asController(Employee $employee, ActionRequest $request): Employee
    {
        $this->initialisation($request)->withTab(EmployeeTabsEnum::values());
        return $this->handle($employee);
    }

    public function htmlResponse(Employee $employee, ActionRequest $request): Response
    {
        return Inertia::render(
            'HumanResources/Calendar',
            [
                'title'                                 => __('calendar'),
                'breadcrumbs'                           => $this->getBreadcrumbs($employee),
                'navigation'                            => [
                    'previous' => $this->getPrevious($employee, $request),
                    'next'     => $this->getNext($employee, $request),
                ],
                'pageHead'    => [
                    'title' => $employee->worker_number,
                    'meta'  => [
                        [
                            'name'     => $employee->contact_name,
                            'leftIcon' => [
                                'icon'    => 'fal fa-id-card',
                                'tooltip' => __('Worker number')
                            ]
                        ],

                        $employee->user ?
                            [
                                'name'     => $employee->user->username,
                                'leftIcon' => [
                                    'icon'    => 'fal fa-user',
                                    'tooltip' => __('User')
                                ]
                            ] : []
                    ],
                    'edit'  => $this->canEdit ? [
                        'route' => [
                            'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                            'parameters' => array_values($this->originalParameters)
                        ]
                    ] : false,
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => EmployeeTabsEnum::navigation()
                ]
            ]
        );
    }


    public function jsonResponse(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    public function getBreadcrumbs(Employee $employee, $suffix = null): array
    {
        return array_merge(
            (new HumanResourcesDashboard())->getBreadcrumbs(),
            [
                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => [
                                'name' => 'hr.employees.index',
                            ],
                            'label' => __('employees')
                        ],
                        'model' => [
                            'route' => [
                                'name'       => 'hr.employees.show',
                                'parameters' => [$employee->slug]
                            ],
                            'label' => $employee->worker_number,
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
        if(!$employee) {
            return null;
        }
        return match ($routeName) {
            'hr.employees.show'=> [
                'label'=> $employee->contact_name,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'employee'=> $employee->slug
                    ]

                ]
            ]
        };
    }
}
