<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:55:04 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Http\Resources\Helpers\UploadRecordsResource;
use App\Http\Resources\Helpers\UploadsResource;
use App\Http\Resources\HumanResources\EmployeeResource;
use App\Models\Helpers\Upload;
use App\Models\Helpers\UploadRecord;
use App\Models\Market\Shop;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Closure;
use App\InertiaTable\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexUploads extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.view');
    }

    public function handle($prefix = null): LengthAwarePaginator
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('original_filename', $value);
            });
        });

        if ($prefix) {
            InertiaTable::updateQueryBuilderParameters($prefix);
        }

        $queryBuilder=QueryBuilder::for(Upload::class);

        /** @noinspection PhpUndefinedMethodInspection */
        return $queryBuilder
            ->defaultSort('uploaded_at')
            ->allowedSorts(['uploaded_at'])
            ->allowedFilters([$globalSearch])
            ->withPaginator($prefix)
            ->withQueryString();
    }

    public function asController(): LengthAwarePaginator
    {
        return $this->handle();
    }

    public function inShop(Shop $shop): LengthAwarePaginator
    {
        return $this->handle();
    }

    public function jsonResponse(LengthAwarePaginator $uploads): AnonymousResourceCollection
    {
        return UploadsResource::collection($uploads);
    }

    public function htmlResponse(LengthAwarePaginator $uploads, ActionRequest $request): Response
    {
        return Inertia::render(
            'Upload/Upload',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'    => __('Uploads'),
                'pageHead' => [
                    'title'     => __('Uploads'),
                    'iconRight' => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-upload'
                    ],
                ],
                'data' => UploadsResource::collection($uploads),
            ]
        )->table($this->tableStructure(prefix: 'upload_histories'));
    }

    public function tableStructure($prefix=null): Closure
    {
        return function (InertiaTable $table) use ($prefix) {
            if ($prefix) {
                $table
                    ->name($prefix)
                    ->pageName($prefix.'Page');
            }
            $table
                ->withGlobalSearch()
                ->column(key: 'original_filename', label: __('Original Filename'), canBeHidden: false, sortable: true)
                ->column(key: 'number_rows', label: __('Number Rows'), canBeHidden: false, sortable: true)
                ->column(key: 'number_success', label: __('Number Success'), canBeHidden: false, sortable: true)
                ->column(key: 'number_fails', label: __('Number Fails'), canBeHidden: false, sortable: true)
                ->column(key: 'uploaded_at', label: __('Uploaded At'), canBeHidden: false, sortable: true)
                ->defaultSort('created_at');
        };
    }

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
            'org.crm.shop.prospects.uploads.index' =>
            array_merge(
                IndexProspects::make()->getBreadcrumbs($routeName, $routeParameters),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'label' => __('uploads'),
                            'icon'  => 'fal fa-bars'
                        ],
                    ],
                ],
            ),

            default => []
        };
    }
}
