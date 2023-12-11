<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:55:04 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads\UI;

use App\Actions\InertiaAction;
use App\Actions\Leads\Prospect\UI\IndexProspects;
use App\Http\Resources\Helpers\UploadsResource;
use App\Models\Helpers\Upload;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

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

    public function htmlResponse(Upload $upload, ActionRequest $request): Response
    {
        return Inertia::render(
            'Upload/Upload',
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
                'data' => new UploadsResource($upload)
            ]
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
