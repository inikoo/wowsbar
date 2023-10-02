<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithUserFields;
use App\Models\Auth\CustomerUser;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditCustomerUser extends InertiaAction
{
    use WithUserFields;

    public function handle(CustomerUser $customerUser): CustomerUser
    {
        return $customerUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("sysadmin.view");
    }

    public function asController(CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $this->initialisation($request);

        return $this->handle($customerUser);
    }


    public function htmlResponse(CustomerUser $customerUser, ActionRequest $request): Response
    {
        $fields = [
            [
                'title'   => __('credentials'),
                'icon'    => 'fal fa-key',
                'current' => false,
                'fields'  => [
                    'email'    => [
                        'type'  => 'input',
                        'label' => __('email'),
                        'value' => $customerUser->user->email
                    ],
                    'password' => [
                        'type'  => 'password',
                        'label' => __('password'),
                        'value' => ''
                    ],
                ]
            ],
            [
                'title'   => __('name'),
                'icon'    => 'fal fa-user',
                'current' => true,
                'fields'  => [
                    'contact_name' => [
                        'type'  => 'input',
                        'label' => __('name'),
                        'value' => $customerUser->user->contact_name
                    ],


                ]
            ],
        ];
        if (!$customerUser->is_root) {
            $fields = array_merge(
                $fields,
                [
                    [
                        'title'   => __('Status'),
                        'icon'    => 'fal fa-toggle-on',
                        'current' => false,
                        'fields'  => [
                            'status' => [
                                'type'      => 'toggleSquare',
                                'typeLabel' => ['suspended', 'active'],
                                'label'     => __('Status'),
                                'value'     => $customerUser->status
                            ],
                        ]
                    ],

                    [
                        'title'   => __('Permissions'),
                        'icon'    => 'fal fa-user-lock',
                        'current' => false,
                        'fields'  => [
                            'permissions' => [
                                'type'    => 'select',
                                'label'   => __('permissions'),
                                'options' => $customerUser->getRoleNames(),
                                'value'   => $customerUser->getRoleNames(),
                                // 'fullComponentArea' => true,
                            ],
                        ]
                    ],
                ]
            );
        }


        return Inertia::render(
            'EditModel',
            [
                'title'       => __('user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'pageHead'    => [
                    'title'   => $customerUser->user->email,
                    'icon'    => [
                        'icon'   => 'fal fa-terminal',
                        'tooltip'=> __('User')
                    ],
                    'noCapitalise'=> true,
                    'actions'     => [
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
                    'blueprint' => $fields,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'customer.models.user.update',
                            'parameters' => [$customerUser->id]

                        ],
                    ]
                ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowCustomerUser::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }
}
