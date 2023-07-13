<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 29 May 2023 12:18:36 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banners\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Http\Resources\Portfolio\WebsiteResource;
use App\InertiaTable\InertiaTable;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use App\Models\Tenancy\Tenant;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexBanners extends InertiaAction
{

    private Tenant|Website $parent;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('portfolio.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.view')
            );
    }

    public function inTenant(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent=app('currentTenant');
        return $this->handle();
    }

    public function inWebsite(Website $website,ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        $this->parent=$website;
        return $this->handle();
    }

    protected function getElementGroups(): void
    {
        $this->elementGroups =
            [

            ];
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('content_blocks.name', $value)
                    ->orWhere('content_blocks.code', 'ilike', "$value%");
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(ContentBlock::class);
        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        return $queryBuilder
            ->defaultSort('content_blocks.code')
            ->select(['content_blocks.code', 'content_blocks.name', 'content_blocks.slug'])
            ->allowedSorts(['slug', 'code', 'name'])
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

            foreach ($this->elementGroups as $key => $elementGroup) {
                $table->elementGroup(
                    key: $key,
                    label: $elementGroup['label'],
                    elements: $elementGroup['elements']
                );
            }

            $table
                ->withModelOperations($modelOperations)
                ->withGlobalSearch()
                ->withEmptyState(
                    [
                        'title' => __('No websites found'),
                        'count' => app('currentTenant')->stats->number_websites,

                    ]
                )
                ->column(key: 'slug', label: __('code'), sortable: true)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->defaultSort('slug');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return WebsiteResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $websites, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/Websites',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('websites'),
                'pageHead'    => [
                    'title'   => __('websites'),
                    'icon'    => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions' => [
                        $this->canEdit ? [
                            'type'    => 'button',
                            'style'   => 'create',
                            'tooltip' => __('Create website'),
                            'label'   => __('new website'),
                            'route'   => [
                                'name' => 'portfolio.websites.create',
                            ]
                        ] : false,


                    ]
                ],
                'data'        => WebsiteResource::collection($websites),

            ]
        )->table($this->tableStructure());
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
            'portfolio.websites.index' =>
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'portfolio.websites.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
