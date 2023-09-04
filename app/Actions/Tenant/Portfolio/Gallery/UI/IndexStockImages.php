<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:58 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Gallery\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolio;
use App\Http\Resources\Gallery\ImageResource;
use App\InertiaTable\InertiaTable;
use App\Models\Media\LandlordMedia;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexStockImages extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.images.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $this->initialisation($request);

        return $this->handle();
    }



    /** @noinspection PhpUndefinedMethodInspection */
    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('media.name', $value);
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(LandlordMedia::class);


        return $queryBuilder
            ->defaultSort('media.name')
            ->where('collection_name', 'stock_images')
            ->select(['media.name','media.id','size','mime_type','file_name','disk','media.slug','is_animated'])
            ->allowedSorts(['name','size'])
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
                        'title' => __('No images found'),
                        'count' => app('currentTenant')->stats->number_websites,

                    ]
                )
                ->withExportLinks($exportLinks)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'thumbnail', label: __('image'))
                ->column(key: 'size', label: __('size'), sortable: true)
                ->column(key: 'select', label: __(' '))

                ->defaultSort('name');
        };
    }

    public function jsonResponse(): AnonymousResourceCollection
    {
        return ImageResource::collection($this->handle());
    }

    public function htmlResponse(LengthAwarePaginator $websites, ActionRequest $request): Response
    {
        return Inertia::render(
            'Tenant/Portfolio/StockImages',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'    => __('images'),
                'pageHead' => [
                    'title'     => __('images'),
                    'iconRight' => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-images'
                    ],
                ],
                'data' => ImageResource::collection($websites),

            ]
        )->table($this->tableStructure(
            modelOperations: [
                'createLink' => [
                    'route' => [
                        'name'       => 'portfolio.websites.create',
                        'parameters' => array_values([])
                    ],
                    'type'    => 'button',
                    'style'   => 'primary',
                    'tooltip' => __('upload image'),
                    'label'   => __('upload image'),
                    'icon'    => 'fas fa-upload'
                ]
            ]
        ));
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
                        'label' => __('images'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'portfolio.images.index' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'portfolio.images.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
