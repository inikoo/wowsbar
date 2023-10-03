<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 20:25:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\UI;

use App\Actions\InertiaAction;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateCustomerUser extends InertiaAction
{
    public function handle(ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs($request->route()->getName()),
                'title'       => __('new user'),
                'pageHead'    => [
                    'title'        => __('new user'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'customer.sysadmin.users.index',
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'   => __('credentials'),
                            'icon'    => 'fal fa-key',
                            'fields' => [

                                'email' => [
                                    'type'  => 'input',
                                    'label' => __('email'),
                                    'value' => ''
                                ],
                                'password' => [
                                    'type'  => 'password',
                                    'label' => __('password'),
                                    'value' => ''
                                ],
                                'contact_name' => [
                                    'type'  => 'input',
                                    'label' => __('name'),
                                    'value' => ''
                                ],

                            ]
                        ],
                        [
                            'title'   => __('Permissions'),
                            'icon'    => 'fal fa-user-lock',
                            'current' => false,
                            'fields'  => [
                                'roles' => [
                                    'type'    => 'customerRoles',
                                    'label'   => __('roles'),
                                    'value'   =>[]
                                ],
                            ]
                        ],
                    ],
                    'route'      => [
                        'name'       => 'customer.models.user.store',
                    ]
                ],


            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo('sysadmin.users.edit');
    }


    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle($request);
    }

    public function getBreadcrumbs(string $routeName): array
    {
        return array_merge(
            IndexCustomerUsers::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating user'),
                    ]
                ]
            ]
        );
    }
}
