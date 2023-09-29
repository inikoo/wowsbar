<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Gallery\UI\UploadedImages;

use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Models\Media\Media;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowUploadedImage extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()>hasPermissionTo('portfolio.images.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()>hasPermissionTo('portfolio.images.view')
            );
    }

    public function asController(Media $media): Media
    {
        return $this->handle($media);
    }

    public function handle(Media $media): Media
    {
        return $media;
    }

    public function htmlResponse(Media $media, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/Image',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => [],
                ],
                'title'    => __('images'),
                'pageHead' => [
                    'title'     => __($media->name),
                    'iconRight' => [
                        'title' => __('image'),
                        'icon'  => 'fal fa-images'
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
                                'name'       => 'customer.portfolio.images.remove',
                                'parameters' => [$media->slug]
                            ]
                        ]
                    ],
                ],
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
