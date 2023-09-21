<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Enums\UI\Organisation\ShopsTabsEnum;
use App\Http\Resources\Market\ShopResource;
use App\InertiaTable\InertiaTable;
use App\Models\Market\Shop;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexShops extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('shops');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('shops.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(ShopsTabsEnum::values());

        return $this->handle();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('shops.name', $value)
                    ->orWhere('shops.code', 'ilike', "$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Shop::class);
        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }

        return $queryBuilder
            ->defaultSort('shops.code')
            ->select(['code', 'id', 'name', 'slug', 'type'])
            ->allowedSorts(['code', 'name', 'type'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure($prefix): Closure
    {
        return function (InertiaTable $table) use ($prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            $table
                ->withGlobalSearch()
                ->withModelOperations()
                ->withEmptyState(
                    [
                        'title'       => __('No shops found'),
                        'description' => $this->canEdit ? __('Get started by creating a shop. ✨') : null,
                        'count'       => organisation()->stats->number_shops,
                        'action'      => $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new shop'),
                            'label'   => __('shop'),
                            'route'   => [
                                'name'       => 'org.shops.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : null
                    ]
                )
                ->column(key: 'code', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'type', label: __('type'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('code');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return ShopResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $shops, ActionRequest $request): Response
    {
        return Inertia::render(
            'Market/Shops',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('shops'),
                'pageHead'    => [
                    'title'   => __('shops'),
                    'icon'    => [
                        'icon'  => ['fal', 'fa-store-alt'],
                        'title' => __('shop')
                    ],
                    'actions' => [
                        $this->canEdit && $request->route()->getName() == 'org.shops.index' ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('new shop'),
                            'label'   => __('shop'),
                            'route'   => [
                                'name'       => 'org.shops.create',
                                'parameters' => $request->route()->originalParameters()
                            ]
                        ] : false,
                    ]
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => ShopsTabsEnum::navigation(),
                ],


                ShopsTabsEnum::SHOPS->value => $this->tab == ShopsTabsEnum::SHOPS->value ?
                    fn () => ShopResource::collection($shops)
                    : Inertia::lazy(fn () => ShopResource::collection($shops)),
                /*
                                ShopsTabsEnum::PRODUCTS->value => $this->tab == ShopsTabsEnum::PRODUCTS->value ?
                                    fn () => ProductResource::collection(IndexProducts::run($scope))
                                    : Inertia::lazy(fn () => ProductResource::collection(IndexProducts::run($scope))),
                */

            ]
        )->table($this->tableStructure(prefix: 'shops'));
    }

    public function getBreadcrumbs($suffix = null): array
    {
        return
            array_merge(
                (new ShowDashboard())->getBreadcrumbs(),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'org.shops.index'
                            ],
                            'label' => __('shops'),
                            'icon'  => 'fal fa-bars'
                        ],
                        'suffix' => $suffix

                    ]
                ]
            );
    }
}
