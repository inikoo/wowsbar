<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:19:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Subscriptions\CustomerSocialAccount\CustomerSocialAccountPost\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\Portfolio\PortfolioSocialAccount\PortfolioSocialAccountPostTypeEnum;
use App\Enums\UI\Customer\PortfolioSocialAccountTabsEnum;
use App\Http\Resources\Portfolio\PortfolioSocialAccountPostsResource;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Portfolio\SocialPost;
use App\Models\Portfolios\CustomerSocialAccount;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexCustomerSocialAccountPosts extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('portfolio');

        return $request->user()->hasPermissionTo('portfolio');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(PortfolioSocialAccountTabsEnum::values());

        return $this->handle();
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(CustomerSocialAccount $customerSocialAccount = null, $prefix = null, $tab = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('task_name', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(SocialPost::class);

        $queryBuilder->where('type', $tab);
        $queryBuilder->where('portfolio_social_account_id', $customerSocialAccount->id);

        return $queryBuilder
            ->with('platform')
            ->defaultSort('task_name')
            ->allowedSorts(['task_name', 'platform', 'type', 'status'])
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
                    ->pageName($prefix . 'Page');
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title'       => __('No posts found'),
                        'count'       => 0
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'task_name', label: 'Task Name', sortable: true)
                ->column(key: 'start_at', label: __('Uploaded At'), sortable: true);

            if ($prefix == PortfolioSocialAccountPostTypeEnum::ADS->value) {
                $table->column(key: 'end_at', label: __('Deleted At'), sortable: true)
                    ->column(key: 'duration', label: __('Duration'), sortable: true);
            }

            $table->column(key: 'platform', label: ['fal', 'microphone-stand'], sortable: true, type: 'icon')
                ->column(key: 'type', label: __('Type'), sortable: true)
                ->column(key: 'status', label: __('Status'), sortable: true)
                ->column(key: 'description', label: __('Description'), sortable: true)
                ->column(key: 'notes', label: __('Notes'), sortable: true)
                ->defaultSort('task_name');
        };
    }

    public function jsonResponse(LengthAwarePaginator $posts): AnonymousResourceCollection
    {
        return PortfolioSocialAccountPostsResource::collection($posts);
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
                        'label' => __('social accounts'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'customer.portfolio.social-accounts.index' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'customer.portfolio.social-accounts.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
