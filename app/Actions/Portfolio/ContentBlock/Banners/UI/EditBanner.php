<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 19 May 2023 11:40:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\Website\UI\ShowWebsite;
use App\Models\Portfolio\ContentBlock;
use Exception;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditBanner extends InertiaAction
{
    public function handle(ContentBlock $banner): ContentBlock
    {
        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('portfolio.edit');
        return $request->user()->can("portfolio.edit");

    }

    public function asController(ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request);
        return $this->handle($banner);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(ContentBlock $banner, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                    'title'       => __("Website's settings"),
                    'breadcrumbs' => $this->getBreadcrumbs(
                        $request->route()->getName(),
                        $request->route()->parameters()
                    ),
                    'navigation'   => [
                        'previous' => $this->getPrevious($banner, $request),
                        'next'     => $this->getNext($banner, $request),
                    ],
                    'pageHead'    => [
                        'title'     => __('Edit banner'),
                        'container' => [
                            'icon'    => ['fal', 'fa-globe'],
                            'tooltip' => __('Website'),
                            'label'   => Str::possessive($banner->name)
                        ],

                        'iconRight'    =>
                            [
                                'icon'  => ['fal', 'fa-edit'],
                                'title' => __("Editing banner")
                            ],

                        'actions'   => [
                            [
                                'type'  => 'button',
                                'style' => 'exitEdit',
                                'label' => __('Exit edit'),
                                'route' => [
                                    'name'       => preg_replace('/edit$/', 'show', $this->routeName),
                                    'parameters' => array_values($this->originalParameters)
                                ]
                            ]
                        ],
                    ],
                    'formData' => [
                        'blueprint' => [
                            [
                                'title'  => __('ID/domain'),
                                'icon'   => 'fa-light fa-id-card',
                                'fields' => [
                                    'code' => [
                                        'type'     => 'input',
                                        'label'    => __('code'),
                                        'value'    => $banner->code,
                                        'required' => true,
                                    ],
                                    'name' => [
                                        'type'     => 'input',
                                        'label'    => __('name'),
                                        'value'    => $banner->name,
                                        'required' => true,
                                    ],
                                ]
                            ],

                    ],
                        'args'      => [
                            'updateRoute' => [
                                'name'       => 'models.banner.update',
                                'parameters' => $banner->slug
                            ],
                        ]
                    ],

                ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowWebsite::make()->getBreadcrumbs(
            $routeName,
            $routeParameters,
            suffix: '('.__('editing').')'
        );
    }

    public function getPrevious(ContentBlock $banner, ActionRequest $request): ?array
    {
        $previous = ContentBlock::where('code', '<', $banner->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(ContentBlock $banner, ActionRequest $request): ?array
    {
        $next = ContentBlock::where('code', '>', $banner->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?ContentBlock $banner, string $routeName): ?array
    {
        if (!$banner) {
            return null;
        }

        return match ($routeName) {
            'portfolio.banners.edit' => [
                'label' => $banner->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'banner' => $banner->slug
                    ]
                ]
            ]
        };
    }
}
