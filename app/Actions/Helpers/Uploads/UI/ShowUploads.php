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
use App\Models\Helpers\Upload;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Closure;
use App\InertiaTable\InertiaTable;

class ShowUploads extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('crm.view');
    }

    public function handle(Upload $upload): Upload
    {
        return $upload;
    }

    public function asController(Upload $upload): Upload
    {
        return $this->handle($upload);
    }

    public function inShop(Shop $shop, Upload $upload): Upload
    {
        return $this->handle($upload);
    }

    public function htmlResponse(Upload $upload, ActionRequest $request): Response
    {
        return Inertia::render(
            'Upload/UploadRecords',
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
                        'icon'  => 'fal fa-images'
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'tertiary',
                            'label' => __('download'),
                            'icon'  => 'fal fa-download',
                            'route' => [
                                'name'       => 'org.uploads.download',
                                'parameters' => $upload->id,
                            ],
                        ],
                    ]
                ],
                'data' => UploadRecordsResource::collection($upload->records),
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
                ->column(key: 'status', label: __('Status'), canBeHidden: false, sortable: true, searchable: true)
                ->column(key: 'row_number', label: __('Row Number'), canBeHidden: false, sortable: true)
                ->column(key: 'fail_column', label: __('Fail Column'), canBeHidden: false, sortable: true)
                ->column(key: 'errors', label: __('Errors'), canBeHidden: false, sortable: true)
                ->column(key: 'created_at', label: __('Created At'), canBeHidden: false, sortable: true)
                ->column(key: 'updated_at', label: __('Updated At'), canBeHidden: false, sortable: true)
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
            'org.shop.prospects.index' =>
            array_merge(
                IndexProspects::make()->getBreadcrumbs($routeName, $routeParameters),
                $headCrumb(
                    [
                        'name' => 'org.uploads.show',
                        $routeParameters
                    ]
                ),
            ),

            default => []
        };
    }
}
