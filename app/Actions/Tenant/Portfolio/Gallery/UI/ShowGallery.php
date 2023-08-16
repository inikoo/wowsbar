<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:58 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Gallery\UI;

use App\Actions\InertiaAction;
use App\Actions\Tenant\Portfolio\Gallery\UI\UploadedImages\IndexUploadedImages;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolioDashboard;
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
            'Tenant/Portfolio/Gallery',
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
                    'actions'   => [
                        [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => 'create Banner',
                            'route' => [
                                'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ]
                    ]
                ],
                'tabs'                             => [
                    'current'    => $this->tab,
                    'navigation' => GalleryTabsEnum::navigation(),
                ],

                GalleryTabsEnum::UPLOADED_IMAGES->value => $this->tab == GalleryTabsEnum::UPLOADED_IMAGES->value
                    ?
                    fn () => ImageResource::collection(
                        IndexUploadedImages::run(
                            prefix: 'uploaded_images'
                        )
                    )
                    : Inertia::lazy(fn () => ImageResource::collection(
                        IndexUploadedImages::run(
                            prefix: 'uploaded_images'
                        )
                    )),
                GalleryTabsEnum::STOCK_IMAGES->value => $this->tab == GalleryTabsEnum::STOCK_IMAGES->value
                    ?
                    fn () => ImageResource::collection(
                        IndexStockImages::run(
                            prefix: 'uploaded_images'
                        )
                    )
                    : Inertia::lazy(fn () => ImageResource::collection(
                        IndexStockImages::run(
                            prefix: 'stock_images'
                        )
                    )),
            ]
        )->table(
            IndexUploadedImages::make()->tableStructure(
                modelOperations: [
                    'uploadFile' => $this->canEdit ? [
                        'route' => [
                            'name'       => 'portfolio.images.upload',
                            'parameters' => []
                        ],
                        'label' => __('Upload image'),
                        'style' => 'create'
                    ] : false,
                ],
                prefix: 'uploaded_images'
            )
        )->table(
            IndexStockImages::make()->tableStructure(
                prefix: 'stock_images'
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
                ShowPortfolioDashboard::make()->getBreadcrumbs(),
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
