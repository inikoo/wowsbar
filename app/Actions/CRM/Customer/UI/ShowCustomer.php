<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\UI;

use App\Actions\CRM\Appointment\UI\IndexAppointments;
use App\Actions\CRM\CustomerWebsite\UI\IndexCustomerWebsites;
use App\Actions\CRM\Shipping\ShipperAccount\UI\IndexShipperAccounts;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioSocialAccount\UI\IndexPortfolioSocialAccounts;
use App\Actions\SysAdmin\UI\CRM\ShowCRMDashboard;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Enums\UI\Customer\CustomerTabsEnum;
use App\Http\Resources\CRM\AppointmentResource;
use App\Http\Resources\CRM\CustomerResource;
use App\Http\Resources\CRM\CustomerWebsitesResource;
use App\Http\Resources\CRM\ShipperAccountResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountResource;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowCustomer extends InertiaAction
{
    use WithActionButtons;

    public function handle(Customer $customer): Customer
    {
        return $customer;
    }


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('crm.customers.edit');
        $this->canDelete = $request->user()->hasPermissionTo('crm.customers.edit');

        return $request->user()->hasPermissionTo("crm.customers.view");
    }

    public function asController(Customer $customer, ActionRequest $request): Customer
    {
        $this->initialisation($request)->withTab(CustomerTabsEnum::values());
        return $this->handle($customer);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inShop(Shop $shop, Customer $customer, ActionRequest $request): Customer
    {
        $this->initialisation($request)->withTab(CustomerTabsEnum::values());
        return $this->handle($customer);
    }

    public function htmlResponse(Customer $customer, ActionRequest $request): Response
    {
        return Inertia::render(
            'CRM/Customer',
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
                    'title'   => $customer->name,
                    'icon'    => [
                        'icon'  => ['fal', 'fa-user'],
                        'title' => __('customer')
                    ],
                    'meta'    => [
                        [
                            'href'     => [
                                'name'       => $request->route()->getName().'.customer-users.index',
                                'parameters' => $request->route()->originalParameters()
                            ],
                            'number'   => $customer->stats->number_customer_users_status_active,
                            'label'    => __('Users'),
                            'leftIcon' => [
                                'icon'    => 'fal fa-terminal',
                                'tooltip' => __('users')
                            ]
                        ],
                        [
                            'href'     => [
                                'name'       => $request->route()->getName().'.customer-websites.index',
                                'parameters' => $request->route()->originalParameters()
                            ],
                            'number'   => $customer->portfolioStats->number_portfolio_websites,
                            'label'    => __('Websites'),
                            'leftIcon' => [
                                'icon'    => 'fal fa-briefcase',
                                'tooltip' => __('portfolio websites')
                            ]
                        ],
                    ],
                    'actions' => [
                        $this->canDelete ? $this->getDeleteActionIcon($request) : null,
                        $this->canEdit ? $this->getEditActionIcon($request) : null,
                    ],

                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => CustomerTabsEnum::navigation()

                ],
                'uploadRoutes' => [
                    'upload' => [
                        'name'       => 'org.models.customer.website.upload',
                        'parameters' => $customer->slug
                    ],
                    // 'history' => [
                    //     'name'       => 'org.models.customer.website.upload',
                    //     'parameters' => $customer->slug
                    // ],
                    // 'download' => [
                    //     'name'       => 'org.crm.prospects.uploads.template.download',
                    //     'parameters' => $customer->slug
                    // ]
                ],

                CustomerTabsEnum::SHOWCASE->value => $this->tab == CustomerTabsEnum::SHOWCASE->value ?
                    fn () => GetCustomerShowcase::run($customer)
                    : Inertia::lazy(fn () => GetCustomerShowcase::run($customer)),

                CustomerTabsEnum::APPOINTMENTS->value => $this->tab == CustomerTabsEnum::APPOINTMENTS->value ?
                    fn () => AppointmentResource::collection(IndexAppointments::run($customer))
                    : Inertia::lazy(fn () => AppointmentResource::collection(IndexAppointments::run($customer))),

                CustomerTabsEnum::WEBSITES->value => $this->tab == CustomerTabsEnum::WEBSITES->value ?
                    fn () => CustomerWebsitesResource::collection(IndexCustomerWebsites::run(parent:$customer, prefix:CustomerTabsEnum::WEBSITES->value))
                    : Inertia::lazy(fn () => CustomerWebsitesResource::collection(IndexCustomerWebsites::run(parent:$customer, prefix:CustomerTabsEnum::WEBSITES->value))),

                CustomerTabsEnum::SOCIAL_ACCOUNT->value => $this->tab == CustomerTabsEnum::SOCIAL_ACCOUNT->value ?
                    fn () => PortfolioSocialAccountResource::collection(IndexPortfolioSocialAccounts::run($customer))
                    : Inertia::lazy(fn () => PortfolioSocialAccountResource::collection(IndexPortfolioSocialAccounts::run($customer))),

                CustomerTabsEnum::SHIPPER_ACCOUNTS->value => $this->tab == CustomerTabsEnum::SHIPPER_ACCOUNTS->value ?
                    fn () => ShipperAccountResource::collection(IndexShipperAccounts::run($customer))
                    : Inertia::lazy(fn () => ShipperAccountResource::collection(IndexShipperAccounts::run($customer))),

            ]
        )->table(IndexAppointments::make()->tableStructure(parent: $customer, prefix: CustomerTabsEnum::APPOINTMENTS->value))
            ->table(IndexCustomerWebsites::make()->tableStructure(
                parent: $customer,
                modelOperations: [
                'createLink' => [
                    /*
                    [
                        'route' => [
                            'name'       => 'org.models.customers.websites.upload',
                            'parameters' => array_values($this->originalParameters)
                        ],
                        'icon'  => 'fal fa-upload',
                        'label' => 'upload',
                        'style' => 'secondary',
                        'mode'  => 'upload', // To be able to call in parent page as template #buttonupload
                    ],
                    */
                    [
                        'route' => [
                            'name'       => 'org.crm.shop.customers.show.customer-websites.create',
                            'parameters' => array_values($this->originalParameters)
                        ],
                        'label' => __('create'),
                        'style' => 'primary'
                    ],
                ]
            ],
                prefix: CustomerTabsEnum::WEBSITES->value,
                // exportLinks: [
                //     'export' => [
                //         'route' => [
                //             'name' => 'export.websites.index'
                //         ]
                //     ]
                // ]
            ))->table(IndexPortfolioSocialAccounts::make()->tableStructure(prefix: CustomerTabsEnum::SOCIAL_ACCOUNT->value))
            ->table(IndexShipperAccounts::make()->tableStructure(parent: $customer, modelOperations: [
            'createLink' => [
                [
                    'route' => [
                        'name'       => 'org.crm.shop.customers.shipper-accounts.create',
                        'parameters' => array_values($this->originalParameters)
                    ],
                    'label' => __('create'),
                    'style' => 'primary'
                ],
            ]
        ], prefix: CustomerTabsEnum::SHIPPER_ACCOUNTS->value));
    }

    public function jsonResponse(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (Customer $customer, array $routeParameters, string $suffix = null) {
            return [
                [

                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('customers')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $customer->name,
                        ],

                    ],
                    'suffix'         => $suffix

                ],
            ];
        };
        return match ($routeName) {
            'org.crm.customers.show',
            'org.crm.customers.edit'
            => array_merge(
                ShowCRMDashboard::make()->getBreadcrumbs('org.crm.dashboard'),
                $headCrumb(
                    Customer::where('slug', $routeParameters['customer'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.customers.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'org.crm.customers.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            'org.crm.shop.customers.show',
            'org.crm.shop.customers.edit'
            => array_merge(
                ShowCRMDashboard::make()->getBreadcrumbs('org.crm.shop.dashboard', $routeParameters),
                $headCrumb(
                    Customer::where('slug', $routeParameters['customer'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.shop.customers.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.shop.customers.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
        };
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
            'org.crm.customers.show' => [
                'label' => $customer->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'customer' => $customer->slug
                    ]
                ]
            ],
            'org.crm.shop.customers.show' => [
                'label' => $customer->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'shop'     => $customer->shop->slug,
                        'customer' => $customer->slug
                    ]
                ]
            ]
        };
    }
}
