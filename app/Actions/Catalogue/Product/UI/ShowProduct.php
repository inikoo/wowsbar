<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product\UI;

use App\Actions\Catalogue\ProductCategory\UI\ShowDepartment;
use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\InertiaAction;
use App\Actions\Market\Shop\UI\IndexShops;
use App\Enums\UI\Organisation\ProductTabsEnum;
use App\Http\Resources\Catalogue\ProductResource;
use App\Models\Catalogue\Product;
use App\Models\Catalogue\ProductCategory;
use App\Models\Organisation\Organisation;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowProduct extends InertiaAction
{

    private ProductCategory|Organisation $parent;

    public function handle(Product $product): Product
    {
        return $product;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('shops.edit');
        $this->canDelete = $request->user()->can('shops.edit');

        return $request->user()->hasPermissionTo("shops.view");
    }


    public function asController(Product $product, ActionRequest $request): Product
    {
        $this->initialisation($request)->withTab(ProductTabsEnum::values());
        $this->parent=organisation();
        return $this->handle($product);
    }

    public function inDepartment(ProductCategory $productCategory,Product $product, ActionRequest $request): Product
    {
        $this->initialisation($request)->withTab(ProductTabsEnum::values());
        $this->parent=$productCategory;
        return $this->handle($product);
    }

    public function htmlResponse(Product $product, ActionRequest $request): Response
    {
        $scope    =$this->parent;
        $container=null;
        if (class_basename($scope) == 'ProductCategory') {
            $container = [
                'href'=>[
                    'name'=>'org.catalogue.departments.show',
                    'parameters'=>[
                        'productCategory'=>$this->parent->slug,
                        '_query'=>[
                            'tab'=>'products'
                        ]
                    ]
                ],
                'icon'    => ['fal', 'fa-folder-tree'],
                'tooltip' => __('Department'),
                'label'   => Str::possessive($scope->code)
            ];
        }

        return Inertia::render(
            'Catalogue/Product',
            [
                'title'       => __('product'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($product, $request),
                    'next'     => $this->getNext($product, $request),
                ],
                'pageHead'    => [
                    'title'   => $product->code,
                    'icon'    =>
                        [
                            'icon'  => ['fal', 'fa-cube'],
                            'title' => __('product')
                        ],
                     'container'=>$container,

                    'actions_todo' => [
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
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => ProductTabsEnum::navigation()
                ],

                ProductTabsEnum::SHOWCASE->value => $this->tab == ProductTabsEnum::SHOWCASE->value ?
                    fn () => GetProductShowcase::run($product)
                    : Inertia::lazy(fn () => GetProductShowcase::run($product)),

                /*                ProductTabsEnum::ORDERS->value => $this->tab == ProductTabsEnum::ORDERS->value ?
                                    fn () => OrderResource::collection(IndexOrders::run($product))
                                    : Inertia::lazy(fn () => OrderResource::collection(IndexOrders::run($product))),

                                ProductTabsEnum::CUSTOMERS->value => $this->tab == ProductTabsEnum::CUSTOMERS->value ?
                                    fn () => CustomerResource::collection(IndexCustomers::run($product))
                                    : Inertia::lazy(fn () => CustomerResource::collection(IndexCustomers::run($product))),

                                ProductTabsEnum::MAILSHOTS->value => $this->tab == ProductTabsEnum::MAILSHOTS->value ?
                                    fn () => MailshotResource::collection(IndexMailshots::run($product))
                                    : Inertia::lazy(fn () => MailshotResource::collection(IndexMailshots::run($product))),*/

                /*
                ProductTabsEnum::IMAGES->value => $this->tab == ProductTabsEnum::IMAGES->value ?
                    fn () => ImagesResource::collection(IndexImages::run($product))
                    : Inertia::lazy(fn () => ImagesResource::collection(IndexImages::run($product))),
                */

            ]
        )//->table(IndexOrders::make()->tableStructure($product))
        ->table(IndexCustomers::make()->tableStructure($product));
        //            ->table(IndexMailshots::make()->tableStructure($product));
    }

    public function jsonResponse(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        $headCrumb = function (Product $product, array $routeParameters, $suffix) {
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
                            'label' => $product->slug,
                        ],
                    ],
                    'suffix'         => $suffix,

                ],

            ];
        };

        return match ($routeName) {
            'org.catalogue.products.show' =>
            array_merge(
                IndexShops::make()->getBreadcrumbs(),
                $headCrumb(
                    Product::where('slug', $routeParameters['product'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.catalogue.products.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'org.catalogue.products.show',
                            'parameters' => $routeParameters

                        ]
                    ],
                    $suffix
                )
            ),
            'org.catalogue.departments.show.products.show' =>
            array_merge(
                ShowDepartment::make()->getBreadcrumbs(
                    'org.catalogue.departments.show',
                    $routeParameters
                ),
                $headCrumb(
                    Product::where('slug', $routeParameters['product'])->first(),
                    [
                        'index' => [
                            'name'       => 'org.catalogue.departments.show.products.index',
                            'parameters' => $routeParameters
                        ],
                        'model' => [
                            'name'       => 'org.catalogue.departments.show.products.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                )
            ),
            default => []
        };
    }

    public function getPrevious(Product $product, ActionRequest $request): ?array
    {
        $previous = Product::where('slug', '<', $product->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Product $product, ActionRequest $request): ?array
    {
        $next = Product::where('slug', '>', $product->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Product $product, string $routeName): ?array
    {
        if (!$product) {
            return null;
        }

        return match ($routeName) {
            'org.catalogue.products.show' => [
                'label' => $product->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'product' => $product->slug
                    ]

                ]
            ],
            'org.catalogue.departments.show.products.show' => [
                'label' => $product->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'productCategory' => $product->parent->slug,
                        'product'         => $product->slug
                    ]

                ]
            ],
        };
    }
}
