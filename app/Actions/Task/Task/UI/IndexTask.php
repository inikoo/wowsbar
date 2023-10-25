<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:19:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Task\Task\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\UI\Customer\PortfolioSocialAccountsTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Task\Task;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexTask extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('tasks.edit');

        return $request->user()->hasPermissionTo('tasks.view');
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle();
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Customer $customer = null, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('types.name', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Task::class);

        return $queryBuilder
            ->defaultSort('date')
            ->allowedSorts(['date', 'types.name'])
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
                        'title' => __('No tasks found'),
                        'count' => 0
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'date', label: __('date'), sortable: true)
                ->column(key: 'task_types', label: __('task type'), sortable: true)
                ->column(key: 'user', label: __('user'), sortable: true)
                ->defaultSort('date');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return PortfolioSocialAccountResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $socialAccounts, ActionRequest $request): Response
    {
        return Inertia::render(
            'Task/Tasks',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('tasks'),
                'pageHead'    => [
                    'title'     => __('tasks'),
                    'iconRight' => [
                        'title' => __('tasks'),
                        'icon'  => 'fal fa-thumbs-up'
                    ],
                    'actions'   => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('Create task'),
                            'route' => [
                                'name'       => 'customer.portfolio.social-accounts.create',
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
                    fn () => HistoryResource::collection(IndexHistory::run(PortfolioSocialAccount::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(PortfolioSocialAccount::class)))
            ]
        )->table(
            $this->tableStructure(
                prefix: 'task',
            )
        )->table(IndexHistory::make()->tableStructure());
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
                        'label' => __('tasks'),
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
