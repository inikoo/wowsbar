<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerWebsite\UI;

use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Portfolios\ShowPortfoliosDashboard;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Organisation\CustomerWebsiteTabsEnum;
use App\Http\Resources\Prospects\CustomerWebsiteResource;
use App\Http\Resources\History\HistoryResource;
use App\Models\CRM\Customer;
use App\Models\Portfolios\CustomerWebsite;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCustomerWebsite extends InertiaAction
{
    use AsAction;
    use WithInertia;


    private Customer|Shop|Organisation $parent;

    public function handle(Organisation|Shop|Customer $parent, CustomerWebsite $customerWebsite): CustomerWebsite
    {
        $this->parent = $parent;

        return $customerWebsite;
    }


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('crm.edit');
        $this->canDelete = $request->user()->hasPermissionTo('crm.edit');

        return $request->user()->hasPermissionTo('crm.view');
    }

    public function asController(CustomerWebsite $customerWebsite, ActionRequest $request): CustomerWebsite
    {
        $this->initialisation($request)->withTab(CustomerWebsiteTabsEnum::values());

        return $this->handle(organisation(), $customerWebsite);
    }

    public function inShop(Shop $shop, CustomerWebsite $customerWebsite, ActionRequest $request): CustomerWebsite
    {
        $this->initialisation($request)->withTab(CustomerWebsiteTabsEnum::values());

        return $this->handle($shop, $customerWebsite);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, CustomerWebsite $customerWebsite, ActionRequest $request): CustomerWebsite
    {
        $this->initialisation($request)->withTab(CustomerWebsiteTabsEnum::values());

        return $this->handle($customer, $customerWebsite);
    }

    public function htmlResponse(CustomerWebsite $customerWebsite, ActionRequest $request): Response
    {
        $scope     = $this->parent;
        $container = null;
        if (class_basename($scope) == 'Customer') {
            $container = [
                'icon'    => ['fal', 'fa-user'],
                'tooltip' => __('Customer'),
                'label'   => Str::possessive($scope->name)
            ];
        }

        return Inertia::render(
            'Portfolios/CustomerWebsite',
            [
                'title'       => __('CustomerWebsite'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($customerWebsite, $request),
                    'next'     => $this->getNext($customerWebsite, $request),
                ],
                'pageHead'    => [
                    'title'     => __('Website').': '.$customerWebsite->name,
                    'iconRight' => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-briefcase'
                    ],
                    'container' => $container,
                    'actions'   => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('edit'),
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : [],
                    ]
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => CustomerWebsiteTabsEnum::navigation()
                ],

                CustomerWebsiteTabsEnum::SHOWCASE->value => $this->tab == CustomerWebsiteTabsEnum::SHOWCASE->value ?
                    fn() => GetCustomerWebsiteShowcase::run($customerWebsite)
                    : Inertia::lazy(fn() => GetCustomerWebsiteShowcase::run($customerWebsite)),


                CustomerWebsiteTabsEnum::CHANGELOG->value => $this->tab == CustomerWebsiteTabsEnum::CHANGELOG->value
                    ?
                    fn() => HistoryResource::collection(
                        IndexHistory::run(
                            model: $customerWebsite,
                            prefix: CustomerWebsiteTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn() => HistoryResource::collection(
                        IndexHistory::run(
                            model: $customerWebsite,
                            prefix: CustomerWebsiteTabsEnum::CHANGELOG->value
                        )
                    )),
                /*
                                CustomerWebsiteTabsEnum::BANNERS->value => $this->tab == CustomerWebsiteTabsEnum::BANNERS->value ?
                                    fn () => BannerResource::collection(IndexBanners::run($customerWebsite))
                                    : Inertia::lazy(fn () => BannerResource::collection(IndexBanners::run($customerWebsite)))
                */
            ]
        )->table(IndexHistory::make()->tableStructure(prefix: CustomerWebsiteTabsEnum::CHANGELOG->value));
    }

    public function jsonResponse(CustomerWebsite $customerWebsite): CustomerWebsiteResource
    {
        return new CustomerWebsiteResource($customerWebsite);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (string $type, CustomerWebsite $customerWebsite, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('customer websites')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $customerWebsite->name,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $customerWebsite->name
                    ],
                    'suffix'         => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'org.crm.shop.customers.show.customer-websites.show',
            'org.crm.shop.customers.show.customer-websites.edit' =>
            array_merge(
                ShowCustomer::make()->getBreadcrumbs(
                    'org.crm.shop.customers.show',
                    $routeParameters
                ),
                $headCrumb(
                    'modelWithIndex',
                    CustomerWebsite::where('slug', $routeParameters['customerWebsite'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.crm.shop.customers.show.customer-websites.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.crm.shop.customers.show.customer-websites.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            'org.portfolios.shop.customer-websites.show',
            'org.portfolios.shop.customer-websites.edit' =>
            array_merge(
                ShowPortfoliosDashboard::make()->getBreadcrumbs(
                    'org.portfolios.shop.dashboard',
                    $routeParameters
                ),
                $headCrumb(
                    'modelWithIndex',
                    CustomerWebsite::where('slug', $routeParameters['customerWebsite'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.portfolios.shop.customer-websites.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.portfolios.shop.customer-websites.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            'org.portfolios.show',
            'org.portfolios.edit' =>
            array_merge(
                IndexCustomerWebsites::make()->getBreadcrumbs(
                    'org.portfolios.index',
                    []
                ),
                $headCrumb(
                    'modelWithIndex',
                    CustomerWebsite::where('slug', $routeParameters['customerWebsite'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.portfolios.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'org.portfolios.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(CustomerWebsite $customerWebsite, ActionRequest $request): ?array
    {
        $previous = CustomerWebsite::where('slug', '<', $customerWebsite->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(CustomerWebsite $customerWebsite, ActionRequest $request): ?array
    {
        $next = CustomerWebsite::where('slug', '>', $customerWebsite->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?CustomerWebsite $customerWebsite, string $routeName): ?array
    {
        if (!$customerWebsite) {
            return null;
        }

        return match ($routeName) {
            'org.portfolios.shop.customer-websites.show',
            'org.crm.shop.customers.show.customer-websites.show' => [
                'label' => $customerWebsite->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'shop'            => $customerWebsite->customer->shop->slug,
                        'customer'        => $customerWebsite->customer->slug,
                        'customerWebsite' => $customerWebsite->slug
                    ]
                ]
            ],
            'org.portfolios.show' => [
                'label' => $customerWebsite->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'customerWebsite' => $customerWebsite->slug
                    ]
                ]
            ]
        };
    }
}
