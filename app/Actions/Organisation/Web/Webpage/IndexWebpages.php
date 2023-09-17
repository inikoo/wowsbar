<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 07 Jul 2023 11:39:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage;

use App\Actions\InertiaAction;
use App\Actions\Organisation\Web\Website\UI\ShowWebsite;
use App\Http\Resources\Web\WebpageResource;
use App\InertiaTable\InertiaTable;
use App\Models\Organisation\Organisation;
use App\Models\Web\Webpage;
use App\Models\Web\Website;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexWebpages extends InertiaAction
{
    /**
     * @var \App\Models\Organisation\Organisation|\App\Models\Web\Webpage|\App\Models\Web\Website
     */
    private Webpage|Website|Organisation $parent;

    public function authorize(ActionRequest $request): bool
    {
        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('websites.view')
            );
    }


    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        return $this->handle(organisation());
    }

    public function inWebsite(Website $website, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        return $this->handle($website);
    }

    public function inWebpage(Webpage $webpage, ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);
        return $this->handle($webpage);
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Website|Webpage|Organisation $parent, $prefix = null): LengthAwarePaginator
    {
        $this->parent =$parent;
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('webpages.code', 'LIKE', "%$value%")
                    ->orWhere('webpages.url', 'LIKE', "%$value%");
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Webpage::class);


        if(class_basename($parent)=='Webpage') {
            $queryBuilder->where('parent_id', $parent->id);
        }


        return $queryBuilder
            ->defaultSort('webpages.level')
            ->select(['code', 'id', 'type', 'slug', 'level', 'purpose'])
            ->allowedSorts(['code', 'type', 'level'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function tableStructure(Website|Webpage|Organisation $parent, ?array $modelOperations = null, $prefix = null): Closure
    {
        return function (InertiaTable $table) use ($modelOperations, $prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }
            $table
                ->withGlobalSearch()
                ->withModelOperations($modelOperations)
                ->withEmptyState(
                    [
                        'title' => __("No webpages found"),
                        'count' => organisation()->stats->number_webpages,
                    ]
                )
                ->column(key: 'level', label: ['fal', 'fa-sort-amount-down-alt'], canBeHidden: false, sortable: true)
                ->column(key: 'type', label: ['fal', 'fa-shapes'], canBeHidden: false)
                ->column(key: 'code', label: __('code'), canBeHidden: false, sortable: true, searchable: true)
                ->defaultSort('level');
        };
    }

    public function jsonResponse(LengthAwarePaginator $webpages): AnonymousResourceCollection
    {
        return WebpageResource::collection($webpages);
    }

    public function htmlResponse(LengthAwarePaginator $webpages, ActionRequest $request): Response
    {
        $container = null;

        return Inertia::render(
            'Web/Webpages',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('webpages'),
                'pageHead'    => [
                    'title'     => __('webpages'),
                    'container' => $container,
                    'iconRight' => [
                        'icon'  => ['fal', 'fa-browser'],
                        'title' => __('webpage')
                    ],
                    'actions'   => [
                        [
                            'type'    => 'buttonGroup',
                            'buttons' => [

                                [
                                    'type'  => 'button',
                                    'style' => 'create',
                                    'label' => 'create webpage',
                                    'route' => [
                                        'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                        'parameters' => array_values($request->route()->originalParameters())
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'data'        => WebpageResource::collection($webpages),

            ]
        )->table($this->tableStructure($this->parent));
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('webpages'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'org.websites.show.webpages.index',
            'org.websites.show.webpages.create'=>
            array_merge(
                (new ShowWebsite())->getBreadcrumbs($routeParameters),
                $headCrumb(
                    [
                        'name'      => 'org.websites.show.webpages.index',
                        'parameters'=> $routeParameters
                    ]
                ),
            ),
            default=> []
        };


    }
}
