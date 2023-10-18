<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 17:02:07 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User\UI;

use App\Actions\InertiaAction;
use App\Actions\Traits\Fields\WithCustomerUserFields;
use App\Models\Auth\CustomerUser;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditOrgCustomerUser extends InertiaAction
{
    use WithCustomerUserFields;

    public function handle(CustomerUser $customerUser): CustomerUser
    {
        return $customerUser;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.edit");
    }

    public function asController(CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $this->initialisation($request);
        return $this->handle($customerUser);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomer(Customer $customer, CustomerUser $customerUser, ActionRequest $request): CustomerUser
    {
        $this->initialisation($request);
        return $this->handle($customerUser);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, CustomerUser $customerUser, ActionRequest $request): CustomerUser
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
                'title'       => __('user'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'pageHead'    => [
                    'title'     => $customerUser->user->email,
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
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'      => 'org.models.customer-user.update',
                            'parameters'=> [$customerUser->id]

                        ],
                    ]
                ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowOrgCustomerUser::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }
}
