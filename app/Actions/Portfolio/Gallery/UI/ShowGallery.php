<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Gallery\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\Gallery\UI\UploadedImages\IndexUploadedImages;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\UI\Tenant\GalleryTabsEnum;
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
                    'actions'   => [
                        [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => 'create Banner',
                            'route' => [
                                'name'       => preg_replace('/index$/', 'create', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
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
                            'name'       => 'customer.portfolio.images.upload',
                            'parameters' => []
                        ],
                        'label' => __('Upload image'),
                        'style' => 'create'
                    ] : false,
                ],
                prefix: 'uploaded_images',
                exportLinks: [
                    'export' => [
                        'route' => [
                            'name' => 'export.uploaded.images.index'
                        ]
                    ]
                ]
            )
        )->table(
            IndexStockImages::make()->tableStructure(
                prefix: 'stock_images',
                exportLinks: [
                    'export' => [
                        'route' => [
                            'name' => 'export.stock.images.index'
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
                        'label' => __('gallery'),
                       // 'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'customer.portfolio.gallery' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'customer.portfolio.gallery',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
