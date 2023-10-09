<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\UI;

use App\Actions\Catalogue\Product\UI\IndexProducts;
use App\Actions\InertiaAction;
use App\Actions\Market\ShopProductCategory\UI\IndexShopDepartments;
use App\Actions\Market\ShopProduct\UI\IndexShopProducts;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Organisation\ShopTabsEnum;
use App\Http\Resources\Catalogue\DepartmentResource;
use App\Http\Resources\Catalogue\ProductResource;
use App\Http\Resources\Market\ShopResource;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowShop extends InertiaAction
{
    use AsAction;
    use WithInertia;

    private bool $createWebsite;

    public function handle(Shop $shop): Shop
    {
        return $shop;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit       = $request->user()->hasPermissionTo('shops.edit');
        $this->canDelete     = $request->user()->hasPermissionTo('shops.edit');
        $this->createWebsite = $request->user()->hasPermissionTo('websites.edit');

        return $request->user()->hasPermissionTo("shops.view");
    }

    public function asController(Shop $shop, ActionRequest $request): Shop
    {
        $this->initialisation($request)->withTab(ShopTabsEnum::values());

        return $this->handle($shop);
    }

    public function htmlResponse(Shop $shop, ActionRequest $request): Response
    {

        $actions = [
            !$shop->website && $this->canEdit ? [
                'type'  => 'button',
                'style' => 'create',
                'label' => __('website'),
                'route' => [
                    'name'       => 'org.shops.show.website.create',
                    'parameters' => $request->route()->originalParameters()
                ]

            ] : false,
            $this->canEdit ? [
                'type'  => 'button',
                'style' => 'edit',
                'route' => [
                    'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                    'parameters' => $request->route()->originalParameters()
                ]
            ] : [],

        ];


        return Inertia::render(
            'Market/Shop',
            [
                'title'       => __('shop'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->parameters
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($shop, $request),
                    'next'     => $this->getNext($shop, $request),
                ],
                'pageHead' => [
                    'title' => $shop->name,
                    'icon'  => [
                        'title' => __('Shop'),
                        'icon'  => 'fal fa-store-alt'
                    ],
                    'actions' => $actions
                ],
                'flatTreeMaps' => [
                    [
                        [
                            'name'  => __('customers'),
                            'icon'  => ['fal', 'fa-user'],
                            'href'  => ['crm.shops.show.customers.index', $shop->slug],
                            'index' => [
                                'number' => $shop->crmStats->number_customers
                            ]
                        ],
                        [
                            'name'  => __('prospects'),
                            'icon'  => ['fal', 'fa-user'],
                            'href'  => ['crm.shops.show.prospects.index', $shop->slug],
                            'index' => [
                                'number' => 'TBD'// $shop->stats->number_customers
                            ]
                        ],
                    ],
                    [
                        [
                            'name'  => __('departments'),
                            'icon'  => ['fal', 'fa-folder-tree'],
                            'href'  => ['org.shops.show.departments.index', $shop->slug],
                            'index' => [
                                'number' => $shop->catalogueStats->number_departments
                            ]
                        ],


                        [
                            'name'  => __('products'),
                            'icon'  => ['fal', 'fa-cube'],
                            'href'  => ['org.shops.show.products.index', $shop->slug],
                            'index' => [
                                'number' => $shop->stats->number_products
                            ]
                        ],
                    ],
                    [
                        [
                            'name'  => __('orders'),
                            'icon'  => ['fal', 'fa-shopping-cart'],
                            'href'  => ['crm.shops.show.orders.index', $shop->slug],
                            'index' => [
                                'number' => $shop->stats->number_orders
                            ]
                        ],
                        [
                            'name'  => __('invoices'),
                            'icon'  => ['fal', 'fa-file-invoice'],
                            'href'  => ['crm.shops.show.invoices.index', $shop->slug],
                            'index' => [
                                'number' => $shop->stats->number_invoices
                            ]
                        ],
                        [
                            'name'  => __('delivery-notes'),
                            'icon'  => ['fal', 'fa-sticky-note'],
                            'href'  => ['crm.shops.show.delivery-notes.index', $shop->slug],
                            'index' => [
                                'number' => $shop->stats->number_deliveries
                            ]
                        ]
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => ShopTabsEnum::navigation()
                ],

                ShopTabsEnum::DEPARTMENTS->value => $this->tab == ShopTabsEnum::DEPARTMENTS->value
                    ?
                    fn () => DepartmentResource::collection(
                        IndexShopDepartments::run(
                            parent: $shop,
                            prefix: 'departments'
                        )
                    )
                    : Inertia::lazy(fn () => DepartmentResource::collection(
                        IndexShopDepartments::run(
                            parent: $shop,
                            prefix: 'departments'
                        )
                    )),


                ShopTabsEnum::PRODUCTS->value => $this->tab == ShopTabsEnum::PRODUCTS->value
                    ?
                    fn () => ProductResource::collection(
                        IndexProducts::run(
                            parent: $shop,
                            prefix: 'products'
                        )
                    )
                    : Inertia::lazy(fn () => ProductResource::collection(
                        IndexProducts::run(
                            parent: $shop,
                            prefix: 'products'
                        )
                    )),

            ]
        )->table(
            IndexShopDepartments::make()->tableStructure(
                modelOperations: [
                    'createLink' => $this->canEdit ? [
                        'route' => [
                            'name'       => 'org.shops.show.departments.create',
                            'parameters' => array_values([$shop->slug])
                        ],
                        'label' => __('department'),
                        'style' => 'create'
                    ] : false
                ],
                prefix: 'departments'
            )
        )->table(
            IndexShopProducts::make()->tableStructure(
                parent: $shop,
                modelOperations: [
                    'createLink' => [
                        $this->canEdit ? [
                            'route' => [
                                'name'       => 'org.shops.show.products.create',
                                'parameters' => array_values([$shop->slug])
                            ],
                            'label' => __('product'),
                            'style' => 'create'
                        ] : false
                    ]
                ],
                prefix: 'products'
            )
        );
    }

    public function prepareForValidation(ActionRequest $request): void
    {
        $this->fillFromRequest($request);

        $this->set('canEdit', $request->user()->hasPermissionTo('hr.edit'));
        $this->set('canViewUsers', $request->user()->hasPermissionTo('users.view'));
    }

    public function jsonResponse(Shop $shop): ShopResource
    {
        return new ShopResource($shop);
    }


    public function getBreadcrumbs(array $routeParameters, $suffix = null): array
    {
        return
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type'           => 'modelWithIndex',
                        'modelWithIndex' => [
                            'index' => [
                                'route' => [
                                    'name' => 'org.shops.index'
                                ],
                                'label' => __('shops'),
                                'icon'  => 'fal fa-bars'
                            ],
                            'model' => [
                                'route' => [
                                    'name'       => 'org.shops.show',
                                    'parameters' => [$routeParameters['shop']->slug]
                                ],
                                'label' => $routeParameters['shop']->slug,
                                'icon'  => 'fal fa-bars'
                            ]


                        ],
                        'suffix' => $suffix,
                    ]
                ]
            );
    }

    public function getPrevious(Shop $shop, ActionRequest $request): ?array
    {
        $previous = Shop::where('code', '<', $shop->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Shop $shop, ActionRequest $request): ?array
    {
        $next = Shop::where('code', '>', $shop->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Shop $shop, string $routeName): ?array
    {
        if (!$shop) {
            return null;
        }

        return match ($routeName) {
            'org.shops.show' => [
                'label' => $shop->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'shop' => $shop->slug
                    ]

                ]
            ]
        };
    }
}
