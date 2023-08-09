<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 09 Aug 2023 10:44:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Gallery\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Http\Resources\Gallery\ImageResource;
use App\InertiaTable\InertiaTable;
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
        $this->canEdit = $request->user()->can('portfolio.images.edit');

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
                $query->whereAnyWordStartWith('media.name', $value);
            });
        });
        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder = QueryBuilder::for(Media::class);
        foreach ($this->elementGroups as $key => $elementGroup) {
            $queryBuilder->whereElementGroup(
                prefix: $prefix,
                key: $key,
                allowedElements: array_keys($elementGroup['elements']),
                engine: $elementGroup['engine']
            );
        }


        return $queryBuilder
            ->defaultSort('media.name')
            ->where('collection_name', 'contentBlock')
            ->select(['media.name','media.id','size','mime_type','file_name','disk','media.slug'])
            ->allowedSorts(['name','size'])
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
                    ->pageName($prefix . 'Page');
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
                        'title' => __('No images found'),
                        'count' => app('currentTenant')->stats->number_websites,

                    ]
                )
                ->column(key: 'name', label: __('name'), sortable: true)
                ->column(key: 'thumbnail', label: __('image'), sortable: true)

                ->column(key: 'size', label: __('size'), sortable: true)

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
            'Portfolio/Images',
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
                ShowDashboard::make()->getBreadcrumbs(),
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
