<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 20:25:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\UI;

use App\Actions\Auth\CustomerUser\UI\IndexCustomerUsers;
use App\Actions\InertiaAction;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateOrganisationUser extends InertiaAction
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
                                'name'       => 'org.sysadmin.users.index',
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('create user'),
                            'fields' => [

                                'username' => [
                                    'type'  => 'input',
                                    'label' => __('username'),
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
                                'email' => [
                                    'type'  => 'input',
                                    'label' => __('email'),
                                    'value' => ''
                                ],
                            ]
                        ]
                    ],
                    'route'      => [
                        'name'       => 'models.user.store',
                    ]
                ],


            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('sysadmin.users.edit');
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
