<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CustomerWebsites\CustomerWebsite\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Enums\UI\Organisation\CustomerWebsitesTabsEnum;
use App\Http\Resources\CustomerWebsites\CustomerWebsiteResource;
use App\Http\Resources\History\HistoryResource;
use App\InertiaTable\InertiaTable;
use App\Models\CustomerWebsites\CustomerWebsite;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCustomerWebsites extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('crm.edit');

        return $request->user()->can('crm.view');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(CustomerWebsitesTabsEnum::values());

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

        $queryBuilder = QueryBuilder::for(CustomerWebsite::class);

        return $queryBuilder
            ->select('customers.name as customer_name', 'portfolio_websites.slug', 'portfolio_websites.name', 'domain')
            ->defaultSort('portfolio_websites.code')
            ->leftJoin('customers', 'customer_id', 'customers.id')
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
                ->column(key: 'customer_name', label: __('customer'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'domain', label: __('domain'), sortable: true)
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
            'CustomerWebsites/CustomerWebsites',
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
                    'navigation' => CustomerWebsitesTabsEnum::navigation()
                ],

                CustomerWebsitesTabsEnum::WEBSITES->value => $this->tab == CustomerWebsitesTabsEnum::WEBSITES->value ?
                    fn () => CustomerWebsiteResource::collection($websites)
                    : Inertia::lazy(fn () => CustomerWebsiteResource::collection($websites)),

                CustomerWebsitesTabsEnum::CHANGELOG->value => $this->tab == CustomerWebsitesTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(CustomerWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(CustomerWebsite::class)))
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
                        'label' => 'upload',
                        'style' => 'secondary'
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
                        'label' => __('customers websites'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.customer-websites.index' =>
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'org.customer-websites.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
