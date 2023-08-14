<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User\UI;

use App\Actions\InertiaAction;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateUser extends InertiaAction
{
    public function handle(ActionRequest $request): Response
    {
        return Inertia::render(
            'Tenant/CreateModel',
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
                                'name'       => 'sysadmin.users.index',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('create marketplace agent'),
                            'fields' => [

                                'username' => [
                                    'type'  => 'input',
                                    'label' => __('username'),
                                    'value' => ''
                                ],
                                'name' => [
                                    'type'  => 'input',
                                    'label' => __('name'),
                                    'value' => ''
                                ],
                                'type' => [
                                    'type'  => 'input',
                                    'label' => __('type'),
                                    'value' => ''
                                ],
                            ]
                        ]
                    ],
                    'route'      => [
                        'name'       => 'models.user.update',
                    ]
                ],


            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('sysadmin.users.edit');
    }


    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle($request);
    }

    public function getBreadcrumbs(string $routeName): array
    {
        return array_merge(
            IndexUsers::make()->getBreadcrumbs(
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
