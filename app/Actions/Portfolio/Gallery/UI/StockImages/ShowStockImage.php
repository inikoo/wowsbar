<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 04 Oct 2023 08:09:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Gallery\UI\StockImages;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\Gallery\UI\ShowGallery;
use App\Enums\UI\Customer\StockImageTabsEnum;
use App\Models\Media\Media;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowStockImage extends InertiaAction
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
            $request->get('customerUser')->hasPermissionTo('portfolio.banners.view')
            );
    }

    public function asController(Media $media, ActionRequest $request): Media
    {
        $this->initialisation($request)->withTab(StockImageTabsEnum::values());

        return $this->handle($media);
    }


    public function htmlResponse(Media $media, ActionRequest $request): Response
    {
        return Inertia::render(
            'Banners/StockImage',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'title'       => __('stock image'),
                'pageHead'    => [
                    'title' => __($media->name),
                    'icon'  => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-image-polaroid'
                    ],

                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => [],
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
            'customer.banners.gallery.stock-images.show' =>
            array_merge(
                ShowGallery::make()->getBreadcrumbs(
                    'customer.banners.gallery',
                    []
                ),
                $headCrumb(
                    Media::firstWhere('slug', $routeParameters['media']),
                    [
                        'name'       => 'customer.banners.gallery.stock-images.show',
                        'parameters' => $routeParameters
                    ]
                ),
            ),

            default => []
        };
    }
}
