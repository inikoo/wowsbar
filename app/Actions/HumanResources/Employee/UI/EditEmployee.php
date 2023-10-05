<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee\UI;

use App\Actions\InertiaAction;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\JobPosition;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class EditEmployee extends InertiaAction
{
    public function handle(Employee $employee): Employee
    {
        return $employee;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function asController(Employee $employee, ActionRequest $request): Employee
    {
        $this->initialisation($request);

        return $this->handle($employee);
    }


    /**
     * @throws Exception
     */
    public function htmlResponse(Employee $employee, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                'title'       => __('employee'),
                'breadcrumbs' => $this->getBreadcrumbs($employee),
                'pageHead'    => [
                    'title'    => $employee->contact_name,
                    'icon'     => [
                        'title' => __('employee'),
                        'icon'  => 'fal fa-user-hard-hat'
                    ],
                    'iconRight'    =>
                        [
                            'icon'  => ['fal', 'fa-edit'],
                            'title' => __("Editing employee")
                        ],
                    'actions'  => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit edit'),
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->parameters)
                            ]
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('personal information'),
                            'fields' => [

                                'contact_name' => [
                                    'type'        => 'input',
                                    'label'       => __('name'),
                                    'placeholder' => __('Name'),
                                    'value'       => $employee->contact_name,
                                    'required'    => true
                                ],
                                'date_of_birth' => [
                                    'type'        => 'date',
                                    'label'       => __('date of birth'),
                                    'placeholder' => __('Date of birth'),
                                    'value'       => $employee->date_of_birth
                                ],
                                'email'         => [
                                    'type'  => 'input',
                                    'label' => __('personal email'),
                                ],


                            ]
                        ],
                        [
                            'title'  => __('Employment'),
                            'fields' => [
                                'worker_number'       => [
                                    'type'     => 'input',
                                    'label'    => __('worker number'),
                                    'required' => true,
                                ],
                                'alias'               => [
                                    'type'     => 'input',
                                    'label'    => __('alias'),
                                    'required' => true,
                                ],
                                'work_email'          => [
                                    'type'  => 'input',
                                    'label' => __('work email'),
                                ],
                                'state'               => [
                                    'type'    => 'radio',
                                    'mode'    => 'card',
                                    'label'   => '',
                                    'value'   => EmployeeStateEnum::HIRED->value,
                                    'options' => [
                                        [
                                            'title'       => __('Hired'),
                                            'description' => __('Will start in future date'),
                                            'value'       => EmployeeStateEnum::HIRED->value
                                        ],
                                        [
                                            'title'       => __('Working'),
                                            'description' => __('Employee already working'),
                                            'value'       => EmployeeStateEnum::WORKING->value
                                        ],
                                    ]
                                ],
                                'employment_start_at' => [
                                    'type'     => 'date',
                                    'label'    => __('employment start at'),
                                    'value'    => '',
                                    'required' => true
                                ],

                            ]
                        ],
                        [
                            'title'  => __('job'),
                            'fields' => [

                                'positions' => [
                                    'type'        => 'select',
                                    'label'       => __('position'),
                                    'options'     => Options::forModels(JobPosition::class, label: 'name', value: 'name'),
                                    'placeholder' => __('Select a job position'),
                                    'mode'        => 'single',
                                    'searchable'  => true
                                ],
                                'job_title' => [
                                    'type'        => 'input',
                                    'label'       => __('job title'),
                                    'placeholder' => __('Job title'),
                                    'searchable'  => true
                                ],
                                'required'  => true


                            ]
                        ],
                        [
                            'title'  => __('User credentials'),
                            'fields' => [

                                'username' => [
                                    'type'  => 'input',
                                    'label' => __('username'),

                                ],
                                'password' => [
                                    'type'  => 'password',
                                    'label' => __('password'),

                                ],

                            ]
                        ],

                    ],
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.employee.update',
                            'parameters' => $employee->slug

                        ],
                    ]

                ],

            ]
        );
    }

    public function getBreadcrumbs(Employee $employee): array
    {
        return ShowEmployee::make()->getBreadcrumbs(employee:$employee, suffix: '('.__('editing').')');
    }
}
