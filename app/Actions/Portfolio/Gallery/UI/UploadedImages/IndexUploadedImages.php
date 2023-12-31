<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:15 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Gallery\UI\UploadedImages;

use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Http\Resources\Gallery\ImageResource;
use App\InertiaTable\InertiaTable;
use App\Models\CRM\Customer;
use App\Models\Media\Media;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexUploadedImages extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->get('customerUser')->hasPermissionTo('portfolio.banners.view')
            );
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {
        $customer = $request->get('customer');
        $this->initialisation($request);

        return $this->handle($customer);
    }


    /** @noinspection PhpUndefinedMethodInspection */
    public function handle(Customer $customer, $prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('media.name', $value);
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Media::class);


        return $queryBuilder
            ->defaultSort('media.name')
            ->where('customer_id', $customer->id)
            ->where('collection_name', 'content_block')
            ->select(['media.name', 'media.id', 'size', 'mime_type', 'file_name', 'disk', 'media.slug', 'media.created_at', 'media.is_animated'])
            ->allowedSorts(['name', 'size', 'created_at'])
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
                ->withExportLinks($exportLinks)
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'thumbnail', label: __('image'))
                ->column(key: 'size', label: __('size'), sortable: true)
                ->column(key: 'created_at', label: __('uploaded at'), sortable: true)
                ->column(key: 'select', label: __('Operations'))
                ->defaultSort('name');
        };
    }

    public function jsonResponse(LengthAwarePaginator $images): AnonymousResourceCollection
    {
        return ImageResource::collection($images);
    }

    public function htmlResponse(LengthAwarePaginator $images, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/Images',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => __('stock images'),
                'pageHead'    => [
                    'title'     => __('stock images'),
                    'iconRight' => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-image-polaroid'
                    ],
                ],
                'data'        => ImageResource::collection($images),
            ]
        )->table(
            $this->tableStructure(
                exportLinks: [
                    'export' => [
                        'route' => [
                            'name' => 'customer.export.stock.images.index'
                        ]
                    ]
                ]
            )
        );
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
                        'label' => __('stock images'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };


        return match ($routeName) {
            'customer.portfolio.images.index' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'customer.portfolio.images.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
