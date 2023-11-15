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
use App\Enums\UI\Organisation\ProspectsTagsTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Tag\TagResource;
use App\InertiaTable\InertiaTable;
use App\Models\Leads\Prospect;
use App\Models\Market\Shop;
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
        $this->initialisation($request)->withTab(ProspectsTagsTabsEnum::values());

        return $this->handle();
    }

    public function inShop(Shop $shop, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request)->withTab(ProspectsTagsTabsEnum::values());

        return $this->handle($shop);
    }

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('tags.name', '~*', "\y$value\y");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $query = QueryBuilder::for(Tag::class);


        /** @noinspection PhpUndefinedMethodInspection */
        return $query
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
                        'title'       => 'no tags found',
                        'description' => null,
                        'count'       => 0
                    ]
                )
                ->column(key: 'name', label: __('name'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'type', label: __('type'))
                ->column(key: 'created_at', label: __('created at'));
        };
    }


    public function htmlResponse(LengthAwarePaginator $prospects, ActionRequest $request): Response
    {

        return Inertia::render(
            'CRM/Prospects/Tags',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters(),
                ),
                'title'        => __('prospect tags'),
                'pageHead'     => [
                    'title'   => __('prospect tags'),


                ],

                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => ProspectsTagsTabsEnum::navigation(),
                ],

                'tags' => TagResource::collection(Tag::all()),

                ProspectsTagsTabsEnum::HISTORY->value   => $this->tab == ProspectsTagsTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: ProspectsTagsTabsEnum::HISTORY->value))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(model: Prospect::class, prefix: ProspectsTagsTabsEnum::HISTORY->value))),


            ]
        )->table($this->tableStructure(prefix: ProspectsTagsTabsEnum::TAGS->value))
            ->table(IndexHistory::make()->tableStructure(prefix: ProspectsTagsTabsEnum::HISTORY->value));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, $suffix = null): array
    {
        return match ($routeName) {
            'org.crm.shop.prospects.tags.index' =>
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
