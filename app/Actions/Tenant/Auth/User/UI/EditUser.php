<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\User\UI;

use App\Actions\InertiaAction;
use App\Models\Auth\User;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditUser extends InertiaAction
{
    public function handle(User $user): User
    {
        return $user;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("sysadmin.view");
    }

    public function asController(User $user, ActionRequest $request): User
    {
        $this->initialisation($request);

        return $this->handle($user);
    }



    public function htmlResponse(User $user, ActionRequest $request): Response
    {
        return Inertia::render(
            'Tenant/EditModel',
            [
                'title'       => __('user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'pageHead'    => [
                    'title'     => $user->username,
                    'actions'   => [
                      [
                          'type'  => 'button',
                          'style' => 'exit',
                          'route' => [
                              'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                              'parameters' => array_values($request->route()->originalParameters())
                          ]
                      ]
                    ],
                ],

                'formData' => [
                    'blueprint' => [
                        [
                            'title'    => __('id'),
                             'icon'    => 'fal fa-user',
                             'current' => true,
                            'fields'   => [
                                'contact_name' => [
                                    'type'  => 'input',
                                    'label' => __('name'),
                                    'value' => $user->contact_name
                                ],
                                'username' => [
                                    'type'  => 'input',
                                    'label' => __('username'),
                                    'value' => $user->username
                                ],
                                'email' => [
                                    'type'  => 'input',
                                    'label' => __('email'),
                                    'value' => $user->email
                                ],

                            ]
                        ],
                        [
                            'title'    => __('Status'),
                            'icon'     => 'fal fa-user-lock',
                            'current'  => true,
                            'fields'   => [
                                'status' => [
                                    'type'      => 'toggleSquare',
                                    'typeLabel' => ['suspended', 'active'],
                                    'label'     => __('Status'),
                                    'value'     => $user->status
                                ],
                            ]
                        ],
                        'password'   => [
                            'title'   => __('Password'),
                            'icon'    => 'fal fa-key',
                            'current' => false,
                            'fields'  => [
                                'password' => [
                                    'type'  => 'password',
                                    'label' => __('password'),
                                    'value' => ''
                                ],
                            ]
                        ],
                        'permissions'   => [
                            'title'   => __('Permissions'),
                            'icon'    => 'fal fa-user-lock',
                            'current' => false,
                            'fields'  => [
                                'permissions' => [
                                    'type'              => 'select',
                                    'label'             => __('permissions'),
                                    'options'           => ['tenant','public','pricing'],
                                    // 'fullComponentArea' => true,
                                ],
                            ]
                        ],

                    ],
                    'args' => [
                        'updateRoute' => [
                            'name'      => 'models.user.update',
                            'parameters'=> [$user->username]

                        ],
                    ]
                ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowUser::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }
}
