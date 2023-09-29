<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Employee\UI;

use App\Actions\InertiaAction;
use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Models\HumanResources\JobPosition;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreateEmployee extends InertiaAction
{
    /**
     * @throws Exception
     */
    public function handle(): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new employee'),
                'pageHead'    => [
                    'title'        => __('new employee'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.hr.employees.index',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('personal information'),
                            'fields' => [
                                'contact_name' => [
                                    'type'     => 'input',
                                    'label'    => __('name'),
                                    'required' => true,
                                ],
                                'date_of_birth' => [
                                    'type'  => 'date',
                                    'label' => __('date of birth'),
                                    'value' => ''
                                ],


                            ]
                        ],
                        [
                            'title'  => __('job'),
                            'fields' => [

                                'position' => [
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


                            ]
                        ],
                        [
                            'title'  => __('state'),
                            'fields' => [


                                'state' => [
                                    'type'        => 'select',
                                    'label'       => __('state'),
                                    'options'     => Options::forEnum(EmployeeStateEnum::class),
                                    'placeholder' => __('Select a state'),
                                    'mode'        => 'single',
                                    'searchable'  => true
                                ]

                            ]
                        ]

                    ],
                    'route'      => [
                            'name'       => 'org.models.employee.store',

                    ]

                ],
            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()>hasPermissionTo('hr.edit');
    }


    /**
     * @throws Exception
     */
    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle();
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexEmployees::make()->getBreadcrumbs(),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating employee'),
                    ]
                ]
            ]
        );
    }

}
