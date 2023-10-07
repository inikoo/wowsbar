<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 22:43:44 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Appointment\UI;

use App\Actions\InertiaAction;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateAppointment extends InertiaAction
{
    public function handle(Organisation|Shop $parent, ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('new appointment'),
                'pageHead'    => [
                    'title'   => __('new appointment'),
                    'icon'    => [
                        'icon'  => ['fal', 'fa-handshake'],
                        'title' => __('appointment')
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => match ($request->route()->getName()) {
                                    'shops.show.appointments.create' => 'org.shops.appointments.index',
                                    default                          => preg_replace('/create$/', 'index', $request->route()->getName())
                                },
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' =>
                        [
                            [
                                'title'  => __('customer'),
                                'fields' => [


                                ]
                            ],

                        ],
                    'route'     =>
                        match(class_basename($parent)) {
                            'Shop'=> [
                                'name'       => 'org.models.shop.appointment.store',
                                'parameters' => [$parent->id]
                            ],
                            default=> [
                                [
                                    'name'      => 'org.models.appointment.store',
                                ]
                            ]
                        }
                ]

            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.edit');
    }


    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);
        return $this->handle(organisation(), $request);
    }

    public function inShop(Shop $shop, ActionRequest $request): Response
    {
        $this->initialisation($request);
        return $this->handle($shop, $request);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexAppointments::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating appointment'),
                    ]
                ]
            ]
        );
    }
}
