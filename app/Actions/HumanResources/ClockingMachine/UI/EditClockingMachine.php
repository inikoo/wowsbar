<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine\UI;

use App\Actions\InertiaAction;
use App\Models\HumanResources\ClockingMachine;
use App\Models\HumanResources\Workplace;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditClockingMachine extends InertiaAction
{
    public function handle(ClockingMachine $clockingMachine): ClockingMachine
    {
        return $clockingMachine;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()>hasPermissionTo('hr.clocking-machines.edit');
        return $request->user()->hasPermissionTo("hr.workplaces.edit");
    }

    public function asController(ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $this->initialisation($request);

        return $this->handle($clockingMachine);
    }

    public function inOrganisation(ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $this->initialisation($request);

        return $this->handle($clockingMachine);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWorkplace(Workplace $workplace, ClockingMachine $clockingMachine, ActionRequest $request): ClockingMachine
    {
        $this->initialisation($request);
        return $this->handle($clockingMachine);
    }



    public function htmlResponse(ClockingMachine $clockingMachine, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                'title'       => __('clocking machines'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'pageHead'    => [
                    'title'     => $clockingMachine->code,
                    'actions'   => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('edit clocking machine'),
                            'fields' => [
                                'code' => [
                                    'type'  => 'input',
                                    'label' => __('code'),
                                    'value' => $clockingMachine->code
                                ]
                            ]
                        ]

                    ],
                    'args' => [
                        'updateRoute' => [
                            'name'      => 'models.clocking-machine.update',
                            'parameters'=> $clockingMachine->slug

                        ],
                    ]
                ]

            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowClockingMachine::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }
}
