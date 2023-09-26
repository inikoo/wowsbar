<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 12:11:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\ProductCategory\UI;

use App\Actions\Catalogue\Product\UI\IndexProducts;
use App\Actions\CRM\Customer\UI\IndexCustomers;
use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;

use App\Enums\UI\Organisation\DepartmentTabsEnum;
use App\Http\Resources\Market\DepartmentResource;
use App\Http\Resources\Market\ProductResource;
use App\Models\Catalogue\ProductCategory;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowDepartment extends InertiaAction
{
    public function handle(ProductCategory $department): ProductCategory
    {
        return $department;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('catalogue.edit');
        $this->canDelete = $request->user()->can('catalogue.edit');
        return $request->user()->hasPermissionTo("catalogue.view");
    }

    public function asController(ProductCategory $department, ActionRequest $request): ProductCategory
    {
        $this->initialisation($request)->withTab(DepartmentTabsEnum::values());

        return $this->handle($department);
    }

    public function htmlResponse(ProductCategory $department, ActionRequest $request): Response
    {
        return Inertia::render(
            'Catalogue/Department',
            [
                'title'                              => __('department'),
                'breadcrumbs'                        => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'                            => [
                    'previous' => $this->getPrevious($department, $request),
                    'next'     => $this->getNext($department, $request),
                ],
                'pageHead'                           => [
                    'title' => $department->name,
                    'icon'  => [
                        'icon'  => ['fal', 'fa-folder-tree'],
                        'title' => __('department')
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
                                'name'       => 'shops.show.departments.remove',
                                'parameters' => $request->route()->originalParameters()
                            ]
                        ] : false
                    ]
                ],
                'tabs'                               => [
                    'current'    => $this->tab,
                    'navigation' => DepartmentTabsEnum::navigation()
                ],

                DepartmentTabsEnum::SHOWCASE->value => $this->tab == DepartmentTabsEnum::SHOWCASE->value ?
                    fn () => GetProductCategoryShowcase::run($department)
                    : Inertia::lazy(fn () => GetProductCategoryShowcase::run($department)),

                DepartmentTabsEnum::CUSTOMERS->value => $this->tab == DepartmentTabsEnum::CUSTOMERS->value ?
                    fn () => CustomerResource::collection(
                        IndexCustomers::run(
                            parent: $department->shop,
                            prefix: 'customers'
                        )
                    )
                    : Inertia::lazy(fn () => CustomerResource::collection(
                        IndexCustomers::run(
                            parent: $department->shop,
                            prefix: 'customers'
                        )
                    )),

                DepartmentTabsEnum::PRODUCTS->value  => $this->tab == DepartmentTabsEnum::PRODUCTS->value ?
                    fn () => ProductResource::collection(
                        IndexProducts::run(
                            parent: $department,
                            prefix: 'products'
                        )
                    )
                    : Inertia::lazy(fn () => ProductResource::collection(
                        IndexProducts::run(
                            parent: $department,
                            prefix: 'products'
                        )
                    )),

                DepartmentTabsEnum::HISTORY->value => $this->tab == DepartmentTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($department))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($department))),




            ]
        )->table(
            IndexCustomers::make()->tableStructure(
                parent: $department,
                prefix: 'customers'
            )
        )
            ->table(
                IndexProducts::make()->tableStructure(
                    parent: $department,
                    modelOperations: [
                        'createLink' => $this->canEdit ? [
                            'route' => [
                                'name'       => 'shops.departments.show.products.create',
                                'parameters' => array_values([$department->shop->slug])
                            ],
                            'label' => __('mailshot')
                        ] : false
                    ],
                    prefix: 'products'
                )
            )
            ->table(IndexHistories::make()->tableStructure());
    }


    public function jsonResponse(ProductCategory $department): DepartmentResource
    {
        return new DepartmentResource($department);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        $headCrumb = function (ProductCategory $department, array $routeParameters, $suffix) {
            return [

                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('departments')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $department->slug,
                        ],
                    ],
                    'suffix'         => $suffix,

                ],

            ];
        };

        return match ($routeName) {
            'shops.departments.show' =>
            array_merge(
                IndexShops::make()->getBreadcrumbs(),
                $headCrumb(
                    $routeParameters['department'],
                    [
                        'index' => [
                            'name'       => 'shops.departments.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'shops.departments.show',
                            'parameters' => [
                                $routeParameters['department']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            'shops.show.departments.show' =>
            array_merge(
                ShowShop::make()->getBreadcrumbs($routeParameters),
                $headCrumb(
                    $routeParameters['department'],
                    [
                        'index' => [
                            'name'       => 'shops.show.departments.index',
                            'parameters' => [$routeParameters['shop']->slug]
                        ],
                        'model' => [
                            'name'       => 'shops.show.departments.show',
                            'parameters' => [
                                $routeParameters['shop']->slug,
                                $routeParameters['department']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            default => []
        };
    }

    public function getPrevious(ProductCategory $department, ActionRequest $request): ?array
    {
        $previous = ProductCategory::where('code', '<', $department->code)->orderBy('code', 'desc')->first();
        return $this->getNavigation($previous, $request->route()->getName());

    }

    public function getNext(ProductCategory $department, ActionRequest $request): ?array
    {
        $next = ProductCategory::where('code', '>', $department->code)->orderBy('code')->first();
        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?ProductCategory $department, string $routeName): ?array
    {
        if(!$department) {
            return null;
        }
        return match ($routeName) {
            'shops.departments.show'=> [
                'label'=> $department->name,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'department'=> $department->slug
                    ]
                ]
            ],
            'shops.show.departments.show'=> [
                'label'=> $department->name,
                'route'=> [
                    'name'      => $routeName,
                    'parameters'=> [
                        'shop'      => $department->shop->slug,
                        'department'=> $department->slug
                    ]
                ]
            ],
        };
    }
}
