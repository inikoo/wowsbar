<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 09 Aug 2023 10:44:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Gallery\UI\UploadedImages;

use App\Actions\InertiaAction;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Models\Media\Media;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditUploadedImage extends InertiaAction
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

    public function jsonResponse(): AnonymousResourceCollection
    {
//        return ImageResource::collection($this->handle());
    }

    public function htmlResponse(Media $media, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
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
                    'actions'   => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit edit'),
                            'route' => [
                                'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                'parameters' => [$media->slug]
                            ]
                        ]
                    ],
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('Metadata'),
                            'icon'   => 'fa-light fa-id-card',
                            'fields' => [
                                'name' => [
                                    'type'     => 'input',
                                    'label'    => __('Name'),
                                    'value'    => $media->name,
                                    'required' => true,
                                ]
                            ]
                        ]
                    ],
                    'args'      => [
                        'updateRoute' => [
                            'name'       => 'models.images.update',
                            'parameters' => $media->slug
                        ],
                    ]
                ]
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
                ShowDashboard::make()->getBreadcrumbs(),
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
