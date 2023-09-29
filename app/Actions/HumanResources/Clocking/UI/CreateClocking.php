<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Clocking\UI;

use App\Actions\InertiaAction;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Employee;
use App\Models\HumanResources\Workplace;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreateClocking extends InertiaAction
{
    public function handle(ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'    => __('new clocking'),
                'pageHead' => [
                    'title'        => __('new clocking'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.hr.workplaces.show.clockings.index',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('id'),
                            'fields' => [
                                'generator_id' => [
                                    'type'        => 'select',
                                    'label'       => __('employee'),
                                    'placeholder' => __('Select a employee'),
                                    'options'     => Options::forModels(Employee::class, 'contact_name', 'id'),
                                    'required'    => true,
                                    'searchable'  => true
                                ],
                                'date' => [
                                    'type'     => 'date',
                                    'label'    => __('date'),
                                    'required' => true
                                ],
                                'time' => [
                                    'type'     => 'time',
                                    'label'    => __('time'),
                                    'required' => true
                                ],
                            ]
                        ],


                    ],
                    'route' => match ($request->route()->getName()) {
                        'org.hr.workplaces.show.clockings.create' => [
                            'name'       => 'models.workplace.clocking.store',
                            'parameters' => [$request->route()->parameters['workplace']->slug]
                        ],
                        default => [
                            'name'       => 'models.clocking-machine.clocking.store',
                            'parameters' => [
                                $request->route()->parameters['clockingMachine']->slug
                            ]
                        ]
                    }
                ],

            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('hr');
    }


    public function inWorkplaceInClockingMachine(Workplace $workplace, ClockingMachine $clockingMachine, ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle($request);
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexClockings::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating clocking'),
                    ]
                ]
            ]
        );
    }
}
