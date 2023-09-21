<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\ClockingMachine\UI;

use App\Actions\InertiaAction;
use App\Models\HumanResources\Workplace;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateClockingMachine extends InertiaAction
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
                'title'       => __('new clocking machine'),
                'pageHead'    => [
                    'title'        => __('new clocking machine'),
                    'cancelCreate' => [
                        'route' => [
                            'name'       => 'hr.working-places.show.clocking-machines.index',
                            'parameters' => array_values($this->originalParameters)
                        ],
                    ]

                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('create clocking machine'),
                            'fields' => [
                                'code' => [
                                    'type'        => 'input',
                                    'label'       => __('code'),
                                ],
                            ]
                        ],
                    ],
                    'route'     => [
                        'name'      => 'models.working-place.clocking-machine.store',
                        'arguments' => [$request->route()->parameters['workplace']->slug]
                    ]
                ],

            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('hr.clocking-machines.edit');
    }


    public function asController(Workplace $workplace, ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle($request);
    }



    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {

        return array_merge(
            IndexClockingMachines::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating clocking machines'),
                    ]
                ]
            ]
        );
    }
}
