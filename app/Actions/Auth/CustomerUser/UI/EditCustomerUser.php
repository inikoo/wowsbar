<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithCustomerUserFields;
use App\Models\Auth\CustomerUser;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditCustomerUser extends InertiaAction
{
    use WithCustomerUserFields;

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
        $sections['properties'] = [
            'label'  => __('User properties'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                'email'        => [
                    'type'  => 'input',
                    'label' => __('email'),
                    'value' => $customerUser->user->email
                ],
                'password'     => [
                    'type'  => 'password',
                    'label' => __('password'),
                    'value' => ''
                ],
                'contact_name' => [
                    'type'  => 'input',
                    'label' => __('name'),
                    'value' => $customerUser->user->contact_name
                ],
            ]
        ];


        if (!$customerUser->is_root) {
            $sections['permissions'] = [
                'label'  => __('Permissions'),
                'icon'   => 'fal fa-shield',
                'fields' => [
                    'roles' => [
                        'type'  => 'customerRoles',
                        'label' => __('roles'),
                        'value' => $customerUser->getRoleNames()->all(),
                    ],
                ]
            ];
            $sections['status']      = [
                'label'  => __('Status'),
                'icon'   => 'fal fa-toggle-on',
                'fields' => [
                    'status' => [
                        'type'      => 'toggleSquare',
                        'typeLabel' => ['suspended', 'active'],
                        'label'     => __('Status'),
                        'value'     => $customerUser->status
                    ],
                ]
            ];
        }

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }

        return Inertia::render(
            'EditModel',
            [
                'title'       => __('edit user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'pageHead'    => [
                    'title'        => $customerUser->user->email,
                    'icon'         => [
                        'icon'    => 'fal fa-terminal',
                        'tooltip' => __('User')
                    ],
                    'noCapitalise' => true,
                    'actions'      => [
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
                    'current'   => $currentSection,
                    'blueprint' => $sections,
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
