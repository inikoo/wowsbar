<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Sep 2023 08:52:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Catalogue\Product\UI;

use App\Actions\Catalogue\ProductCategory\UI\ShowDepartment;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Catalogue\ShowCatalogueDashboard;
use App\Http\Resources\Catalogue\ProductResource;
use App\InertiaTable\InertiaTable;
use App\Models\Catalogue\Product;
use App\Models\Catalogue\ProductCategory;
use App\Models\SysAdmin\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProducts extends InertiaAction
{
    private ProductCategory|Organisation $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('catalogue.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('catalogue.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = organisation();
        return $this->handle($this->parent);
    }

    public function inDepartment(ProductCategory $productCategory, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent = $productCategory;
        return $this->handle($productCategory);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(ProductCategory|Organisation $parent, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('products.name', $value)
                    ->orWhereStartWith('products.slug', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }


        $queryBuilder = QueryBuilder::for(Product::class);
        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }



        return $queryBuilder
            ->defaultSort('products.slug')
            ->select([
                'products.code',
                'products.name',
                'products.state',
                'products.created_at',
                'products.updated_at',
                'products.slug',
            ])
            ->leftJoin('product_stats', 'products.id', 'product_stats.product_id')
            ->when($parent, function ($query) use ($parent) {
                if (class_basename($parent) == 'ProductCategory') {
                    $query->where('products.parent_type', 'ProductCategory')->where('products.parent_id', $parent->id);
                }
            })
            ->allowedSorts(['slug', 'name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(ProductCategory|Organisation $parent, ?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($parent, $modelOperations, $prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }
            $table
                ->withGlobalSearch()
                ->withModelOperations($modelOperations)
                ->column(key: 'slug', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true);
        };
    }

    public function jsonResponse(LengthAwarePaginator $products): AnonymousResourceCollection
    {
        return ProductResource::collection($products);
    }

    public function htmlResponse(LengthAwarePaginator $products, ActionRequest $request): Response
    {
        $scope    =$this->parent;
        $container=null;
        if (class_basename($scope) == 'ProductCategory') {
            $container = [
                'icon'    => ['fal', 'fa-folder-tree'],
                'tooltip' => __('Department'),
                'label'   => Str::possessive($scope->name)
            ];
        }



        return Inertia::render(
            'Catalogue/Products',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('Products'),
                'pageHead'    => [
                    'title'        => __('products'),
                    'container'    => $container,
                    'iconRight'    => [
                        'icon'  => ['fal', 'fa-cube'],
                        'title' => __('product')
                    ],

                ],
                'data'        => ProductResource::collection($products),


            ]
        )->table($this->tableStructure($this->parent));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = null): array
    {
        $headCrumb = function (array $routeParameters, ?string $suffix) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('products'),
                        'icon'  => 'fal fa-bars'
                    ],
                    'suffix' => $suffix
                ]
            ];
        };

        return match ($routeName) {
            'org.catalogue.products.index' =>
            array_merge(
                ShowCatalogueDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name'       => $routeName,
                        'parameters' => $routeParameters
                    ],
                    $suffix
                )
            ),

            'org.catalogue.departments.show.products.index' =>
            array_merge(
                ShowDepartment::make()->getBreadcrumbs(
                    'org.catalogue.departments.show',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name'       => $routeName,
                        'parameters' => $routeParameters
                    ],
                    $suffix
                )
            ),

            default => []
        };
    }
}
