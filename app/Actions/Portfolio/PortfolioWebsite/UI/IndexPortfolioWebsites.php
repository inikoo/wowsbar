<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\UI\Organisation\PortfolioWebsitesTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\PortfolioWebsite;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexPortfolioWebsites extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('portfolio');

        return !$request->user()->can('portfolio');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(PortfolioWebsitesTabsEnum::values());

        return $this->handle();
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('portfolio_websites.name', $value)
                    ->orWhere('portfolio_websites.domain', 'ilike', "%$value%")
                    ->orWhere('portfolio_websites.code', 'ilike', "$value%");
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(PortfolioWebsite::class);

        return $queryBuilder
            ->defaultSort('portfolio_websites.code')
            ->with(['customer', 'stats'])
//            ->select(['portfolio_websites.code', 'portfolio_websites.name', 'portfolio_websites.slug', 'portfolio_websites.domain'])
            ->allowedSorts(['slug', 'code', 'name','number_banners','domain'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('No websites found'),
                        'count' => 0
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'slug', label: __('code'), sortable: true)
                ->column(key: 'customer_name', label: __('customer name'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'domain', label: __('domain'), sortable: true)
                ->column(key: 'number_banners', label: __('banners'), sortable: true)
                ->defaultSort('slug');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return PortfolioWebsiteResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $websites, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/PortfolioWebsites',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('customer websites'),
                'pageHead'    => [
                    'title'     => __('customers websites'),
                    'iconRight' => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioWebsitesTabsEnum::navigation()
                ],

                PortfolioWebsitesTabsEnum::WEBSITES->value => $this->tab == PortfolioWebsitesTabsEnum::WEBSITES->value ?
                    fn () => PortfolioWebsiteResource::collection($websites)
                    : Inertia::lazy(fn () => PortfolioWebsiteResource::collection($websites)),

                PortfolioWebsitesTabsEnum::CHANGELOG->value => $this->tab == PortfolioWebsitesTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class)))
            ]
        )->table($this->tableStructure(
            modelOperations: [
                'createLink' => [
                    [
                        'route' => [
                            'name'       => 'org.shops.show.products.create',
                            'parameters' => array_values(['$shop->slug'])
                        ],
                        'icon'  => 'fal fa-upload',
                        'label' => __('upload'),
                        'style' => 'secondary'
                    ],
                    [
                        'route' => [
                            'name'       => 'org.shops.show.products.create',
                            'parameters' => array_values(['$shop->slug'])
                        ],
                        'icon'  => 'fal fa-download',
                        'label' => __('Download'),
                        'style' => 'tertiary'
                    ],
                    [
                        'route' => [
                            'name'       => 'org.shops.show.products.create',
                            'parameters' => array_values(['$shop->slug'])
                        ],
                        'label' => __('create'),
                        'style' => 'primary'
                    ],

                ]
            ],
            prefix: 'websites',
            exportLinks: [
                'export' => [
                    'route' => [
                        'name' => 'export.websites.index'
                    ]
                ]
            ]
        ))->table(IndexHistories::make()->tableStructure());
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('websites'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'customer.portfolio.websites.index' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'customer.portfolio.websites.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
