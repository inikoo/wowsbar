<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\User\UI;

use App\Actions\InertiaAction;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateOrgCustomerUser extends InertiaAction
{
    public function handle(Customer $customer, ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('new user'),
                'pageHead'    => [
                    'title'   => __('new user'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('credentials'),
                            'icon'   => 'fal fa-key',
                            'fields' => [

                                'email'        => [
                                    'type'  => 'input',
                                    'label' => __('email'),
                                    'value' => ''
                                ],
                                'password'     => [
                                    'type'  => 'password',
                                    'label' => __('password'),
                                    'value' => ''
                                ],
                                'contact_name' => [
                                    'type'  => 'input',
                                    'label' => __('name'),
                                    'value' => ''
                                ]
                            ]
                        ],
                        [
                            'title'   => __('Permissions'),
                            'icon'    => 'fal fa-user-lock',
                            'current' => false,
                            'fields'  => [
                                'roles' => [
                                    'type'  => 'customerRoles',
                                    'label' => __('roles'),
                                    'value' => []
                                ],
                            ]
                        ],
                    ],

                    'route' => [
                        'name'       => 'org.models.customer.customer-user.store',
                        'parameters' => $customer->id
                    ]
                ],


            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.edit');
    }


    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle($customer, $request);
    }

    public function inCustomer(Customer $customer, ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle($customer, $request);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexOrgCustomerUsers::make()->getBreadcrumbs(
                preg_replace('/create$/', 'index', $routeName),
                $routeParameters
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
