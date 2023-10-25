<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:19:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Task\TaskActivity\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\UI\Organisation\TaskActivityTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\PortfolioSocialAccountResource;
use App\Http\Resources\Task\TaskActivityResource;
use App\InertiaTable\InertiaTable;
use App\Models\Auth\Guest;
use App\Models\CRM\Customer;
use App\Models\HumanResources\Employee;
use App\Models\Portfolio\PortfolioSocialAccount;
use App\Models\Task\Task;
use App\Models\Task\TaskActivity;
use App\Models\Task\TaskType;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexTaskActivity extends InertiaAction
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
    public function handle(Employee|Guest $parent = null, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('author.username', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(TaskActivity::class);
        $queryBuilder->with(['task', 'activity', 'author']);

        if($parent) {
            $queryBuilder->where([['author_id', $parent->id], ['author_type', $parent::class]]);
        }

        return $queryBuilder
            ->defaultSort('date')
            ->allowedSorts(['date'])
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
                        'title' => __('No task activities found'),
                        'count' => 0
                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'author_name', label: 'Author Name', sortable: true)
                ->column(key: 'activity_name', label: __('activity name'), sortable: true)
                ->column(key: 'task', label: __('task'), sortable: true)
                ->column(key: 'date', label: __('date'), sortable: true)
                ->defaultSort('date');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return TaskActivityResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $taskActivities, ActionRequest $request): Response
    {
        return Inertia::render(
            'Task/TaskActivities',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('task activities'),
                'pageHead'    => [
                    'title'     => __('task activities'),
                    'iconRight' => [
                        'title' => __('task activities'),
                        'icon'  => 'fal fa-thumbs-up'
                    ],
                    'actions'   => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('Create task activity'),
                            'route' => [
                                'name'       => 'customer.portfolio.social-accounts.create',
                                'parameters' => $request->route()->originalParameters()
                            ],

                        ] : null
                    ]

                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => TaskActivityTabsEnum::navigation()
                ],

                TaskActivityTabsEnum::ACTIVITIES->value => $this->tab == TaskActivityTabsEnum::ACTIVITIES->value ?
                    fn () => TaskActivityResource::collection($taskActivities)
                    : Inertia::lazy(fn () => TaskActivityResource::collection($taskActivities)),

                TaskActivityTabsEnum::CHANGELOG->value => $this->tab == TaskActivityTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(TaskActivity::class))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(TaskActivity::class)))
            ]
        )->table(
            $this->tableStructure(
                prefix: 'task_activity',
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
                        'label' => __('task activities'),
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
