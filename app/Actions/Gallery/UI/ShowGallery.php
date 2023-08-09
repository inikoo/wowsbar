<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 09 Aug 2023 10:44:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Gallery\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Enums\UI\GalleryTabsEnum;
use App\Http\Resources\Gallery\ImageResource;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowGallery extends InertiaAction
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

    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request)->withTab(GalleryTabsEnum::values());
        return $request;
    }



    public function htmlResponse(ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/Gallery',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'    => __('gallery'),
                'pageHead' => [
                    'title'     => __('gallery'),
                    'iconRight' => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-photo-video'
                    ],
                ],
                'tabs'                             => [
                    'current'    => $this->tab,
                    'navigation' => GalleryTabsEnum::navigation(),
                ],

                GalleryTabsEnum::UPLOADED_IMAGES->value => $this->tab == GalleryTabsEnum::UPLOADED_IMAGES->value
                    ?
                    fn () => ImageResource::collection(
                        IndexImages::run(
                            prefix: 'uploaded_images'
                        )
                    )
                    : Inertia::lazy(fn () => ImageResource::collection(
                        IndexImages::run(
                            prefix: 'uploaded_images'
                        )
                    )),

            ]
        )->table(
            IndexImages::make()->tableStructure(
                prefix: 'uploaded_images'
                /* modelOperations: [
                      'createLink' => $this->canEdit ? [
                          'route' => [
                              'name'       => 'inventory.warehouses.show.warehouse-areas.create',
                              'parameters' => array_values([$warehouse->slug])
                          ],
                          'label' => __('area'),
                          'style' => 'create'
                      ] : false,
                  ],
                  prefix: 'warehouse_areas' */
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
                        'label' => __('gallery'),
                       // 'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'portfolio.gallery' =>
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'portfolio.gallery',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
