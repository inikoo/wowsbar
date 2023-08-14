<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 09 Aug 2023 10:44:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Gallery\UI\UploadedImages;

use App\Actions\InertiaAction;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolioDashboard;
use App\Models\Media\Media;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowUploadedImage extends InertiaAction
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
            'Tenant/Portfolio/Image',
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
                                'name'       => 'portfolio.images.remove',
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
            'portfolio.images.index' =>
            array_merge(
                ShowPortfolioDashboard::make()->getBreadcrumbs(),
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
