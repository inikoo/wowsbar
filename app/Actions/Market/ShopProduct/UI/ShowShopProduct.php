<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\ShopProduct\UI;

use App\Actions\Catalogue\Product\UI\GetProductShowcase;
use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\InertiaAction;
use App\Actions\Market\Shop\UI\IndexShops;
use App\Actions\Market\Shop\UI\ShowShop;
use App\Enums\UI\Organisation\ProductTabsEnum;
use App\Http\Resources\Catalogue\ProductResource;
use App\Models\Market\ShopProduct;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowShopProduct extends InertiaAction
{
    public function handle(ShopProduct $shopProduct): ShopProduct
    {
        return $shopProduct;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()>hasPermissionTo('shops.edit');
        $this->canDelete = $request->user()>hasPermissionTo('shops.edit');

        return $request->user()->hasPermissionTo("shops.view");
    }



    /** @noinspection PhpUnusedParameterInspection */
    public function inShop(Shop $shop, ShopProduct $shopProduct, ActionRequest $request): ShopProduct
    {
        $this->initialisation($request)->withTab(ProductTabsEnum::values());

        return $this->handle($shopProduct);
    }

    public function htmlResponse(ShopProduct $shopProduct, ActionRequest $request): Response
    {
        return Inertia::render(
            'Market/Product',
            [
                'title'       => __('product'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'                            => [
                    'previous' => $this->getPrevious($shopProduct, $request),
                    'next'     => $this->getNext($shopProduct, $request),
                ],
                'pageHead'    => [
                    'title'   => $shopProduct->code,
                    'icon'    =>
                        [
                            'icon'  => ['fal', 'fa-cube'],
                            'title' => __('product')
                        ],
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $this->routeName),
                                'parameters' => $request->route()->originalParameters()
                            ]
                        ] : false,
                        $this->canDelete ? [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => 'shops.show.products.remove',
                                'parameters' => $request->route()->originalParameters()
                            ]
                        ] : false
                    ]
                ],
                'tabs'=> [
                    'current'    => $this->tab,
                    'navigation' => ProductTabsEnum::navigation()
                ],

                ProductTabsEnum::SHOWCASE->value => $this->tab == ProductTabsEnum::SHOWCASE->value ?
                    fn () => GetProductShowcase::run($shopProduct)
                    : Inertia::lazy(fn () => GetProductShowcase::run($shopProduct)),

/*                ProductTabsEnum::ORDERS->value => $this->tab == ProductTabsEnum::ORDERS->value ?
                    fn () => OrderResource::collection(IndexOrders::run($shopProduct))
                    : Inertia::lazy(fn () => OrderResource::collection(IndexOrders::run($shopProduct))),

                ProductTabsEnum::CUSTOMERS->value => $this->tab == ProductTabsEnum::CUSTOMERS->value ?
                    fn () => CustomerResource::collection(IndexCustomers::run($shopProduct))
                    : Inertia::lazy(fn () => CustomerResource::collection(IndexCustomers::run($shopProduct))),

                ProductTabsEnum::MAILSHOTS->value => $this->tab == ProductTabsEnum::MAILSHOTS->value ?
                    fn () => MailshotResource::collection(IndexMailshots::run($shopProduct))
                    : Inertia::lazy(fn () => MailshotResource::collection(IndexMailshots::run($shopProduct))),*/

                /*
                ProductTabsEnum::IMAGES->value => $this->tab == ProductTabsEnum::IMAGES->value ?
                    fn () => ImagesResource::collection(IndexImages::run($shopProduct))
                    : Inertia::lazy(fn () => ImagesResource::collection(IndexImages::run($shopProduct))),
                */

            ]
        )//->table(IndexOrders::make()->tableStructure($shopProduct))
            ->table(IndexCustomers::make()->tableStructure($shopProduct));
        //            ->table(IndexMailshots::make()->tableStructure($shopProduct));
    }

    public function jsonResponse(ShopProduct $shopProduct): ProductResource
    {
        return new ProductResource($shopProduct);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        $headCrumb = function (ShopProduct $shopProduct, array $routeParameters, $suffix) {
            return [

                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('products')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $shopProduct->slug,
                        ],
                    ],
                    'suffix'         => $suffix,

                ],

            ];
        };
        return match ($routeName) {
            'org.shops.products.show' =>
            array_merge(
                IndexShops::make()->getBreadcrumbs(),
                $headCrumb(
                    $routeParameters['product'],
                    [
                        'index' => [
                            'name'       => 'shops.products.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'shops.products.show',
                            'parameters' => [
                                $routeParameters['product']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            'org.shops.show.products.show' =>
            array_merge(
                ShowShop::make()->getBreadcrumbs(['shop' => $routeParameters['shop']]),
                $headCrumb(
                    $routeParameters['product'],
                    [
                        'index' => [
                            'name'       => 'shops.show.products.index',
                            'parameters' => [$routeParameters['shop']->slug]
                        ],
                        'model' => [
                            'name'       => 'shops.show.products.show',
                            'parameters' => [
                                $routeParameters['shop']->slug,
                                $routeParameters['product']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            default => []
        };
    }

    public function getPrevious(ShopProduct $shopProduct, ActionRequest $request): ?array
    {
        $previous = ShopProduct::where('slug', '<', $shopProduct->slug)->orderBy('slug', 'desc')->first();
        return $this->getNavigation($previous, $request->route()->getName());

    }

    public function getNext(ShopProduct $shopProduct, ActionRequest $request): ?array
    {
        $next = ShopProduct::where('slug', '>', $shopProduct->slug)->orderBy('slug')->first();
        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?ShopProduct $shopProduct, string $routeName): ?array
    {
        if(!$shopProduct) {
            return null;
        }
        return match ($routeName) {
            'org.shops.products.show'=> [
                'label'=> $shopProduct->name,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'product'=> $shopProduct->slug
                    ]

                ]
            ],
            'org.shops.show.products.show'=> [
                'label'=> $shopProduct->name,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'shop'   => $shopProduct->shop->slug,
                        'product'=> $shopProduct->slug
                    ]

                ]
            ],
        };
    }
}
