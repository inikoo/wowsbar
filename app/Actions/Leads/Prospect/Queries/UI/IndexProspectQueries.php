<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 15:38:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Queries\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Traits\WithProspectsSubNavigation;
use App\Enums\UI\Organisation\ProspectsQueriesTabsEnum;
use App\Http\Resources\CRM\ProspectQueriesResource;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Tag\TagResource;
use App\InertiaTable\InertiaTable;
use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Tags\Tag;

class IndexProspectQueries extends InertiaAction
{
    use WithProspectsSubNavigation;

    private Shop|Organisation $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('crm.prospects.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('crm.prospects.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(ProspectsQueriesTabsEnum::values());
        $this->parent = organisation();

        return $this->handle();
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(ProspectsQueriesTabsEnum::values());
        $this->parent = $shop;

        return $this->handle($shop);
    }

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('queries.name', '~*', "\y$value\y");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $query = QueryBuilder::for(Query::class)
            ->where('model_type', 'Prospect');


        /** @noinspection PhpUndefinedMethodInspection */
        return $query
            ->defaultSort('queries.name')
            ->allowedSorts(['name'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
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
                        'title'       => 'no lists found',
                        'description' => null,
                        'count'       => 0
                    ]
                )
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'description', label: __('description'), sortable: true)
                ->column(key: 'number_items', label: __('prospects'))
                ->column(key: 'actions', label: __('actions'));
        };
    }


    public function htmlResponse(LengthAwarePaginator $prospects, ActionRequest $request): Response
    {
        $subNavigation = $this->getSubNavigation($request);

        return Inertia::render(
            'CRM/Prospects/Queries',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters(),
                ),
                'title'       => __('prospect lists'),
                'pageHead'    => [
                    'title'            => __('prospect lists'),
                    'subNavigation'    => $subNavigation,
                    'actions'          => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('prospect list'),
                            'route' => [
                                'name'       => 'org.crm.shop.prospects.lists.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : []
                    ]
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => ProspectsQueriesTabsEnum::navigation(),
                ],

                'tags' => TagResource::collection(Tag::all()),


                ProspectsQueriesTabsEnum::LISTS->value => $this->tab == ProspectsQueriesTabsEnum::LISTS->value ?
                    fn () => ProspectQueriesResource::collection(IndexProspectQueries::run(prefix: ProspectsQueriesTabsEnum::LISTS->value))
                    : Inertia::lazy(fn () => ProspectQueriesResource::collection(IndexProspectQueries::run(prefix: ProspectsQueriesTabsEnum::LISTS->value))),

                ProspectsQueriesTabsEnum::HISTORY->value => $this->tab == ProspectsQueriesTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: ProspectsQueriesTabsEnum::HISTORY->value))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: ProspectsQueriesTabsEnum::HISTORY->value))),


            ]
        )->table($this->tableStructure(prefix: ProspectsQueriesTabsEnum::LISTS->value))
            ->table(IndexHistory::make()->tableStructure(prefix: ProspectsQueriesTabsEnum::HISTORY->value));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        return match ($routeName) {
            'org.crm.shop.prospects.lists.index',
            'org.crm.shop.prospects.lists.show' =>
            array_merge(
                (new IndexProspects())->getBreadcrumbs(
                    'org.crm.shop.prospects.index',
                    $routeParameters
                ),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name'       => 'org.crm.shop.prospects.lists.index',
                                'parameters' => $routeParameters
                            ],
                            'label' => __('lists'),
                            'icon'  => 'fal fa-bars',
                        ],
                        'suffix' => $suffix

                    ]
                ]
            ),
            default => []
        };
    }


}
