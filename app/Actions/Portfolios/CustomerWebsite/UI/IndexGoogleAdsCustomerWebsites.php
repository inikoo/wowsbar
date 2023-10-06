<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 03 Oct 2023 14:53:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolios\CustomerWebsite\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Catalogue\ShowGoogleAdsDashboard;
use App\Actions\UI\Organisation\Catalogue\ShowSeoDashboard;
use App\Enums\UI\Organisation\CustomerWebsitesTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Prospects\CustomerWebsiteResource;
use App\InertiaTable\InertiaTable;
use App\Models\Organisation\Division;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Portfolios\CustomerWebsite;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexGoogleAdsCustomerWebsites extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('catalogue.google-ads.edit');

        return $request->user()->hasPermissionTo('catalogue.google-ads.view');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(CustomerWebsitesTabsEnum::values());
        return $this->handle();
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $divisionId = Cache::get('google-ads');

        if(! $divisionId) {
            $divisionId = Division::firstWhere('slug', 'google-ads')->id;
            Cache::put('google-ads', $divisionId);
        }

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('portfolio_websites.name', $value)
                    ->orWhere('portfolio_websites.url', 'ilike', "%$value%")
                    ->orWhere('portfolio_websites.slug', 'ilike', "$value%")
                    ->orWhere('portfolio_websites.name', 'ilike', "$value%");
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(CustomerWebsite::class);

        return $queryBuilder
            ->defaultSort('portfolio_websites.name')
            ->join('division_portfolio_websites', 'portfolio_websites.id', 'division_portfolio_websites.portfolio_website_id')
            ->join('portfolio_website_stats', 'portfolio_websites.id', 'portfolio_website_stats.portfolio_website_id')
            ->allowedSorts(['slug', 'name', 'number_banners', 'url'])
            ->where('division_portfolio_websites.division_id', $divisionId)
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
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'url', label: __('url'), sortable: true)
                ->column(key: 'number_banners', label: __('banners'), sortable: true)
                ->defaultSort('slug');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return CustomerWebsiteResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $websites, ActionRequest $request): Response
    {
        return Inertia::render(
            'Catalogue/GoogleAds/GoogleAdsCustomerWebsites',
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


                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => CustomerWebsitesTabsEnum::navigation()
                ],

                CustomerWebsitesTabsEnum::WEBSITES->value => $this->tab == CustomerWebsitesTabsEnum::WEBSITES->value ?
                    fn () => CustomerWebsiteResource::collection($websites)
                    : Inertia::lazy(fn () => CustomerWebsiteResource::collection($websites)),

                CustomerWebsitesTabsEnum::CHANGELOG->value => $this->tab == CustomerWebsitesTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(PortfolioWebsite::class)))
            ]
        )->table(
            $this->tableStructure(
                prefix: 'websites',
                exportLinks: [
                    'export' => [
                        'route' => [
                            'name' => 'export.websites.index'
                        ]
                    ]
                ]
            )
        )->table(IndexHistories::make()->tableStructure());
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
            'org.google-ads.websites.index' =>
            array_merge(
                ShowGoogleAdsDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'org.google-ads.websites.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
