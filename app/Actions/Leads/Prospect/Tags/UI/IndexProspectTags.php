<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 15:38:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\Tags\UI;

use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Actions\Traits\WithProspectsSubNavigation;
use App\Enums\UI\Organisation\TagsTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Tag\CrmTagResource;
use App\InertiaTable\InertiaTable;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
use App\Models\SysAdmin\Organisation;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\Tags\Tag;

class IndexProspectTags extends InertiaAction
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
        $this->initialisation($request)->withTab(TagsTabsEnum::values());
        $this->parent = organisation();

        return $this->handle(prefix: TagsTabsEnum::TAGS->value);
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(TagsTabsEnum::values());
        $this->parent = $shop;

        return $this->handle(prefix: TagsTabsEnum::TAGS->value);
    }

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereWith('tags.label', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $query = QueryBuilder::for(Tag::class);
        $query->where('type', 'crm');
        $query->leftJoin('tag_crm_stats', 'tag_crm_stats.tag_id', 'tags.id');

        /** @noinspection PhpUndefinedMethodInspection */
        return $query
            ->allowedSorts(['label', 'number_prospects'])
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
                        'title'       => __('You dont have any tags'),
                        'description' => null,
                        'count'       => 0
                    ]
                )
                ->column(key: 'label', label: __('label'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'number_prospects', label: __('prospects'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'actions', label: __('actions'), canBeHidden: false);
        };
    }


    public function htmlResponse(LengthAwarePaginator $tags, ActionRequest $request): Response
    {
        $subNavigation = $this->getSubNavigation($request);

        return Inertia::render(
            'CRM/Prospects/Tags',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters(),
                ),
                'title'       => __('prospect tags'),
                'pageHead'    => [
                    'title'              => __('tags'),
                    'subNavigation'      => $subNavigation,
                    'actions'            => [
                        [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('tags'),
                            'route' => [
                                'name'       => 'org.crm.shop.prospects.tags.create',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ]
                    ],
                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => TagsTabsEnum::navigation(),
                ],

                    'create_mailshot' => [
                        'route' => [
                            'name'       => 'org.crm.shop.prospects.mailshots.create',
                            'parameters' => array_values($this->originalParameters)
                        ]
                    ],

                TagsTabsEnum::TAGS->value => $this->tab == TagsTabsEnum::TAGS->value ?
                    fn () => CrmTagResource::collection($tags)
                    : Inertia::lazy(fn () => CrmTagResource::collection($tags)),

                TagsTabsEnum::HISTORY->value => $this->tab == TagsTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: TagsTabsEnum::HISTORY->value))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: TagsTabsEnum::HISTORY->value))),


            ]
        )->table($this->tableStructure(prefix: TagsTabsEnum::TAGS->value))
            ->table(IndexHistory::make()->tableStructure(prefix: TagsTabsEnum::HISTORY->value));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        return match ($routeName) {
            'org.crm.shop.prospects.tags.index',
            'org.crm.shop.customers.tags.index' =>
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
                                'name'       => 'org.crm.shop.prospects.tags.index',
                                'parameters' => $routeParameters
                            ],
                            'label' => __('tags'),
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
