<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:19:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioSocialAccount\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\UI\Customer\PortfolioSocialAccountsTabsEnum;
use App\Enums\UI\Customer\PortfolioWebsitesTabsEnum;
use App\Enums\UI\Organisation\CustomerWebsitesTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountResource;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolio\PortfolioWebsite;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexPortfolioSocialAccount extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('portfolio');

        return $request->get('customerUser')->hasPermissionTo('portfolio');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(PortfolioSocialAccountsTabsEnum::values());

        return $this->handle();
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('username', $value)
                    ->orWhere('url', 'ilike', "%$value%")
                    ->orWhere('username', 'ilike', "$value%");
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(PortfolioSocialAccount::class);

        return $queryBuilder
            ->defaultSort('username')
            ->allowedSorts(['username', 'provider', 'number_followers', 'number_posts'])
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
                        'title' => __('No social account found'),
                        'count' => 0
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'username', label: __('username'), sortable: true)
                ->column(key: 'provider', label: __('type'), sortable: true)
                ->column(key: 'url', label: __('profile url'), sortable: true)
                ->column(key: 'number_posts', label: __('number posts'), sortable: true)
                ->column(key: 'number_followers', label: __('number followers'), sortable: true)
                ->defaultSort('username');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return PortfolioWebsiteResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $socialAccounts, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/PortfolioSocialAccounts',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('social account'),
                'pageHead'    => [
                    'title'     => __('social account'),
                    'iconRight' => [
                        'title' => __('social account'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions'   => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('Create social account'),
                            'route' => [
                                'name'       => 'customer.portfolio.social.account.create',
                                'parameters' => $request->route()->originalParameters()
                            ],

                        ] : null
                    ]

                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioSocialAccountsTabsEnum::navigation()
                ],

                PortfolioSocialAccountsTabsEnum::ACCOUNTS->value => $this->tab == PortfolioSocialAccountsTabsEnum::ACCOUNTS->value ?
                    fn () => PortfolioSocialAccountResource::collection($socialAccounts)
                    : Inertia::lazy(fn () => PortfolioSocialAccountResource::collection($socialAccounts)),

                PortfolioSocialAccountsTabsEnum::CHANGELOG->value => $this->tab == PortfolioSocialAccountsTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run(PortfolioSocialAccount::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run(PortfolioSocialAccount::class)))
            ]
        )->table(
            $this->tableStructure(
                prefix: 'accounts',
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
                        'label' => __('accounts'),
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
