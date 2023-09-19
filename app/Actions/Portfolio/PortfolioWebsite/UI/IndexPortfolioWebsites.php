<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\Uploads\IndexPortfolioWebsiteUploads;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\UI\Tenant\PortfolioWebsitesTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Http\Resources\Portfolio\WebsiteUploadsResource;
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
        $this->canEdit = $request->user()->can('portfolio.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.view')
            );
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
            ->leftJoin('portfolio_website_stats', 'portfolio_website_id', 'portfolio_websites.id')
            ->select(['portfolio_websites.code', 'portfolio_websites.name', 'portfolio_websites.slug', 'portfolio_websites.domain', 'portfolio_website_stats.number_banners'])
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
                        'count' => customer()->stats->number_websites,

                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'slug', label: __('code'), sortable: true)
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
                'title'       => __('websites'),
                'pageHead'    => [
                    'title'     => __('websites'),
                    'iconRight' => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions'   => [
                        [
                            'type'    => 'buttonGroup',
                            'buttons' => [
                                [
                                    'style' => 'secondary',
                                    'icon'  => ['fal', 'fa-upload'],
                                    'label' => 'upload',
                                    'route' => [
                                        'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                        'parameters' => array_values($request->route()->originalParameters())
                                    ],
                                ],
                                [
                                    'type'  => 'button',
                                    'style' => 'create',
                                    'label' => 'create website',
                                    'route' => [
                                        'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                        'parameters' => array_values($request->route()->originalParameters())
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioWebsitesTabsEnum::navigation()
                ],

                PortfolioWebsitesTabsEnum::WEBSITES->value => $this->tab == PortfolioWebsitesTabsEnum::WEBSITES->value ?
                    fn () => PortfolioWebsiteResource::collection($websites)
                    : Inertia::lazy(fn () => PortfolioWebsiteResource::collection($websites)),

                PortfolioWebsitesTabsEnum::UPLOADED_WEBSITES->value => $this->tab == PortfolioWebsitesTabsEnum::UPLOADED_WEBSITES->value ?
                    fn () => WebsiteUploadsResource::collection(IndexPortfolioWebsiteUploads::run())
                    : Inertia::lazy(fn () => WebsiteUploadsResource::collection(IndexPortfolioWebsiteUploads::run())),

                PortfolioWebsitesTabsEnum::CHANGELOG->value => $this->tab == PortfolioWebsitesTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class)))
            ]
        )->table($this->tableStructure(
            prefix: 'websites',
            exportLinks: [
                'export' => [
                    'route' => [
                        'name' => 'export.websites.index'
                    ]
                ]
            ]
        ))->table(IndexPortfolioWebsiteUploads::make()->tableStructure(prefix: PortfolioWebsitesTabsEnum::UPLOADED_WEBSITES->value))
            ->table(IndexHistories::make()->tableStructure());
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
