<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Actions\InertiaAction;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditCustomer extends InertiaAction
{
    public function handle(Customer $customer): Customer
    {
        return $customer;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('crm.customers.edit');
        $this->canDelete = $request->user()->hasPermissionTo('supervisor.crm');

        return $request->user()->hasPermissionTo("shops.customers.edit");
    }

    public function inOrganisation(Customer $customer, ActionRequest $request): Customer
    {
        $this->initialisation($request);

        return $this->handle($customer);
    }

    public function inShop(Shop $shop, Customer $customer, ActionRequest $request): Customer
    {
        $this->initialisation($request);

        return $this->handle($customer);
    }

    public function htmlResponse(Customer $customer, ActionRequest $request): Response
    {
        $sections['properties'] = [
            'label'  => __('Properties'),
            'icon'   => 'fal fa-sliders-h',
            'fields' => [
                'contact_name' => [
                    'type'  => 'input',
                    'label' => __('contact name'),
                    'value' => $customer->contact_name
                ],
                'company_name' => [
                    'type'  => 'input',
                    'label' => __('company'),
                    'value' => $customer->company_name
                ],
                'phone'        => [
                    'type'  => 'phone',
                    'label' => __('Phone'),
                    'value' => $customer->phone
                ],


            ]
        ];

        if($this->canDelete) {
            $sections['delete'] = [
                'label'  => __('Delete'),
                'icon'   => 'fal fa-trash-alt',
                'fields' => [
                    'name' => [
                        'type'   => 'action',
                        'action' => [
                            'type'   => 'button',
                            'style'  => 'delete',
                            'label'  => __('delete customer'),
                            'method' => 'delete',
                            'route'  => [
                                'name'       => 'org.models.customer.delete',
                                'parameters' => [
                                    'customer' => $customer->id
                                ]
                            ]
                        ],
                    ]
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
                'title'       => __('customer'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($customer, $request),
                    'next'     => $this->getNext($customer, $request),
                ],
                'pageHead'    => [
                    'title' => $customer->name,
                    'icon'  => [
                        'title' => __('customer'),
                        'icon'  => 'fal fa-user'
                    ],


                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit edit'),
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ]
                    ]

                ],

                'formData' => [
                    'current'   => $currentSection,
                    'blueprint' => $sections,
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'org.models.customer.update',
                            'parameters' => $customer->id
                        ],
                    ]

                ],

            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowCustomer::make()->getBreadcrumbs(
            routeName: preg_replace('/edit$/', 'show', $routeName),
            routeParameters: $routeParameters,
            suffix: '('.__('editing').')'
        );
    }

    public function getPrevious(Customer $customer, ActionRequest $request): ?array
    {
        $previous = Customer::where('slug', '<', $customer->slug)->when(true, function ($query) use ($customer, $request) {
            if ($request->route()->getName() == 'org.shops.show.customers.show') {
                $query->where('customers.shop_id', $customer->shop_id);
            }
        })->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Customer $customer, ActionRequest $request): ?array
    {
        $next = Customer::where('slug', '>', $customer->slug)->when(true, function ($query) use ($customer, $request) {
            if ($request->route()->getName() == 'org.shops.show.customers.show') {
                $query->where('customers.shop_id', $customer->shop_id);
            }
        })->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Customer $customer, string $routeName): ?array
    {
        if (!$customer) {
            return null;
        }

        return match ($routeName) {
            'org.crm.shop.customers.edit' => [
                'label' => $customer->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => $this->originalParameters
                ]
            ]
        };
    }
}
