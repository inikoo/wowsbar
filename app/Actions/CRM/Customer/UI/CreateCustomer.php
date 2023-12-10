<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:32:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Actions\Assets\Country\UI\GetAddressData;
use App\Actions\InertiaAction;
use App\Http\Resources\Helpers\AddressFormFieldsResource;
use App\Models\Helpers\Address;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateCustomer extends InertiaAction
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
                'title'       => __('new customer'),
                'pageHead'    => [
                    'title'   => __('new customer'),
                    'icon'    => [
                        'icon'  => ['fal', 'fa-user'],
                        'title' => __('customer')
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => match ($request->route()->getName()) {
                                    'shops.show.customers.create' => 'org.shops.customers.index',
                                    default                       => preg_replace('/create$/', 'index', $request->route()->getName())
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
                                'title'  => __('contact'),
                                'fields' => [
                                    'company_name' => [
                                        'type'  => 'input',
                                        'label' => __('company')
                                    ],
                                    'contact_name' => [
                                        'type'  => 'input',
                                        'label' => __('contact name')
                                    ],
                                    'email' => [
                                        'type'  => 'input',
                                        'label' => __('email')
                                    ],
                                    'phone' => [
                                        'type'  => 'phone',
                                        'label' => __('phone')
                                    ],

                                ]
                            ],
                            [
                                'title'  => __('address'),
                                'fields' => [

                                    'address'      => [
                                        'type'    => 'address',
                                        'label'   => __('Address'),
                                        'value'   => AddressFormFieldsResource::make(
                                            new Address(
                                                [
                                                    'country_id' => $parent->country_id,

                                                ]
                                            )
                                        )->getArray(),
                                        'options' => [
                                            'countriesAddressData' => GetAddressData::run()

                                        ]
                                    ]
                                ]
                            ]
                        ],
                    'route'     =>
                        match(class_basename($parent)) {
                            'Shop'=> [
                                'name'       => 'org.models.shop.customer.store',
                                'parameters' => [$parent->id]
                            ],
                            default=> [
                                [
                                    'name'      => 'org.models.customer.store',
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
            IndexCustomers::make()->getBreadcrumbs(
                routeName: preg_replace('/create$/', 'index', $routeName),
                routeParameters: $routeParameters,
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating customer'),
                    ]
                ]
            ]
        );
    }
}
