<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Dec 2023 20:31:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\CustomerWebsite\UI;

use App\Actions\CRM\Customer\UI\ShowCustomer;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\Organisation\Portfolios\ShowPortfoliosDashboard;
use App\Enums\UI\Organisation\CustomerWebsitesTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Prospects\CustomerWebsiteResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Portfolios\CustomerWebsite;
use App\Models\SysAdmin\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCustomerWebsites extends InertiaAction
{
    private Customer|Shop|Organisation $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.edit');

        return $request->user()->hasPermissionTo('crm.view');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(CustomerWebsitesTabsEnum::values());

        return $this->handle(organisation());
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(CustomerWebsitesTabsEnum::values());

        return $this->handle($shop);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inCustomerInShop(Shop $shop, Customer $customer, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(CustomerWebsitesTabsEnum::values());

        return $this->handle($customer);
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Organisation|Shop|Customer $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent = $parent;

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('portfolio_websites.name', $value)
                    ->orWhere('portfolio_websites.url', 'ilike', "%$value%")
                    ->orWhere('portfolio_websites.slug', 'ilike', "$value%");
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(CustomerWebsite::class);

        switch (class_basename($parent)) {
            case 'Customer':
                $queryBuilder->where('customer_id', $parent->id);
                break;
            case 'Shop':
                $queryBuilder->where('portfolio_websites.shop_id', $parent->id);
                break;
        }

        return $queryBuilder
            ->select('customers.name as customer_name', 'portfolio_websites.slug', 'portfolio_websites.name', 'url', 'customers.slug as customer_slug')
            ->defaultSort('portfolio_websites.slug')
            ->leftJoin('customers', 'customer_id', 'customers.id')
            ->allowedSorts(['slug', 'name', 'number_banners', 'url'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(Organisation|Shop|Customer $parent, ?array $modelOperations = null, $prefix = null, ?array $exportLinks = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix, $exportLinks, $parent) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    match (class_basename($parent)) {
                        'Customer' => [
                            'title'       => __("This customer don't have any website"),
                            'description' => $this->canEdit ? __('New website.') : null,
                            'count'       => $parent->portfolioStats->number_portfolio_websites,
                            'action'      => [
                                'type'    => 'button',
                                'style'   => 'create',
                                'tooltip' => __('new website'),
                                'label'   => __('website'),
                                'route'   => [
                                    'name'       => 'org.crm.shop.customers.show.customer-websites.create',
                                    'parameters' => [$parent->shop->slug, $parent->slug]
                                ]
                            ]
                        ],
                        default =>
                        [
                            'title' => __('No websites found'),
                            'count' => 0
                        ]
                    }
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'slug', label: __('code'), sortable: true);

            if (class_basename($parent) != 'Customer') {
                $table->column(key: 'customer_name', label: __('customer'), sortable: true);
            }
            $table
                ->column(key: 'slug', label: __('Code'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'url', label: __('url'), sortable: true)
                ->defaultSort('slug');
        };
    }


    public function htmlResponse(LengthAwarePaginator $websites, ActionRequest $request): Response
    {
        $scope     = $this->parent;
        $container = null;

        $title = __('customers websites');

        if (class_basename($scope) == 'Shop' and organisation()->stats->number_shops > 1) {
            $container = [
                'icon'    => ['fal', 'fa-store-alt'],
                'tooltip' => __('Shop'),
                'label'   => Str::possessive($scope->name)
            ];
        } elseif (class_basename($scope) == 'Customer') {
            $container = [
                'icon'    => ['fal', 'fa-user'],
                'tooltip' => __('Customer'),
                'label'   => Str::possessive($scope->name)
            ];
            $title     = __('websites');
        }


        return Inertia::render(
            'Subscriptions/CustomerWebsites',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('customer websites'),
                'pageHead'    => [
                    'title'     => $title,
                    'container' => $container,
                    'iconRight' => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-briefcase'
                    ],
                    'actions'   =>
                        match (class_basename($this->parent)) {
                            'Customer' => [
                                $this->canEdit ? [
                                    'type'    => 'button',
                                    'style'   => 'create',
                                    'tooltip' => __('new website'),
                                    'label'   => __('website'),
                                    'route'   => [
                                        'name'       => 'org.crm.shop.customers.show.customer-websites.create',
                                        'parameters' => array_values($this->originalParameters)
                                    ]
                                ] : null
                            ],
                            default => null
                        }

                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => CustomerWebsitesTabsEnum::navigation()
                ],

                CustomerWebsitesTabsEnum::WEBSITES->value => $this->tab == CustomerWebsitesTabsEnum::WEBSITES->value ?
                    fn () => CustomerWebsiteResource::collection($websites)
                    : Inertia::lazy(fn () => CustomerWebsiteResource::collection($websites)),

                CustomerWebsitesTabsEnum::CHANGELOG->value => $this->tab == CustomerWebsitesTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(CustomerWebsite::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(CustomerWebsite::class)))
            ]
        )->table(
            $this->tableStructure(
                parent: $this->parent,
                prefix: 'websites',
                // exportLinks: [
                //     'export' => [
                //         'route' => [
                //             'name' => 'export.websites.index'
                //         ]
                //     ]
                // ]
            )
        )->table(IndexHistory::make()->tableStructure());
    }

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
            'org.crm.shop.customers.show.customer-websites.index' =>
            array_merge(
                ShowCustomer::make()->getBreadcrumbs(
                    'org.crm.shop.customers.show',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name'       => 'org.crm.shop.customers.show.customer-websites.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),
            'org.subscriptions.customer-websites.index' =>
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'org.subscriptions.customer-websites.index',
                        null
                    ]
                ),
            ),
            'org.subscriptions.shop.customer-websites.index' =>
            array_merge(
                ShowPortfoliosDashboard::make()->getBreadcrumbs(
                    'org.subscriptions.shop.dashboard',
                    $routeParameters
                ),
                $headCrumb(
                    [
                        'name'       => 'org.subscriptions.shop.customer-websites.index',
                        'parameters' => $routeParameters
                    ]
                ),
            ),

            default => []
        };
    }
}
