<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 07 Oct 2023 22:43:44 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Shipping\ShipperAccount\UI;

use App\Actions\InertiaAction;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Shipper;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateShipperAccount extends InertiaAction
{
    public function handle(Customer $parent, ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'    => __('new shipper account'),
                'pageHead' => [
                    'title' => __('new shipper account'),
                    'icon'  => [
                        'icon'  => ['fal', 'fa-handshake'],
                        'title' => __('shipper account')
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.crm.shop.customers.show',
                                'parameters' => array_merge($request->route()->parameters, ['tab' => 'shipper_accounts'])
                            ]
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' =>
                        [
                            [
                                'title'  => __('shipper account'),
                                'fields' => [
                                    'label' => [
                                        'type'     => 'input',
                                        'label'    => __('label'),
                                        'value'    => '',
                                        'required' => true,
                                    ],
                                    'shipper_id' => [
                                        'type'     => 'select',
                                        'mode'     => 'single',
                                        'label'    => __('shipper'),
                                        'required' => true,
                                        'options'  => GetShipperOptions::run(Shipper::all())
                                    ],
                                    'account_id' => [
                                        'type'     => 'input',
                                        'label'    => __('account id'),
                                        'value'    => '',
                                        'required' => true,
                                    ],
                                    'username' => [
                                        'type'     => 'input',
                                        'label'    => __('username'),
                                        'value'    => '',
                                        'required' => true,
                                    ],
                                    'password' => [
                                        'type'     => 'password',
                                        'label'    => __('password'),
                                        'value'    => '',
                                        'required' => true,
                                    ]
                                ]
                            ],
                        ],
                    'route' => [
                        'name'       => 'org.models.shop.customer.shipper-account.store',
                        'parameters' => [$parent->shop_id, $parent->id]
                    ]
                ]
            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.edit');
    }


    public function inCustomerInShop(Shop $shop, Customer $customer, ActionRequest $request): Response
    {
        $this->initialisation($request);
        return $this->handle($customer, $request);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            IndexShipperAccounts::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating shipper account'),
                    ]
                ]
            ]
        );
    }
}
