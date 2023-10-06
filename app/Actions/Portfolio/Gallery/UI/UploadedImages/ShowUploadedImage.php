<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Gallery\UI\UploadedImages;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\Gallery\UI\ShowGallery;
use App\Enums\UI\Customer\UploadedImageTabsEnum;
use App\Models\Media\Media;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowUploadedImage extends InertiaAction
{
    public function handle(Media $media): Media
    {
        return $media;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->get('customerUser')->hasPermissionTo('portfolio.banners.view')
            );
    }

    public function asController(Media $media, ActionRequest $request): Media
    {
        $this->initialisation($request)->withTab(UploadedImageTabsEnum::values());

        return $this->handle($media);
    }


    public function htmlResponse(Media $media, ActionRequest $request): Response
    {
        return Inertia::render(
            'Banners/UploadedImage',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => [],
                ],
                'title'       => __('image'),
                'pageHead'    => [
                    'title'   => __($media->name),
                    'icon'    => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-cloud-upload'
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('edit'),
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => [$media->slug]
                            ]
                        ],
                        [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => 'customer.caas.gallery.uploaded-images.remove',
                                'parameters' => [$media->slug]
                            ]
                        ]
                    ],
                ],
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (Media $media, array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => $media->slug,
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'customer.caas.gallery.uploaded-images.show' =>
            array_merge(
                ShowGallery::make()->getBreadcrumbs(
                    'customer.caas.gallery',
                    []
                ),
                $headCrumb(
                    Media::firstWhere('slug', $routeParameters['media']),
                    [
                        'name'       => 'customer.caas.gallery.uploaded-images.show',
                        'parameters' => $routeParameters
                    ]
                ),
            ),

            default => []
        };
    }
}
