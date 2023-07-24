<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 14 Jul 2023 14:24:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBannerWorkshop extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('portfolio.edit');
        $this->canDelete = $request->user()->can('portfolio.edit');

        return $request->user()->hasPermissionTo("portfolio.view");
    }

    public function inTenant(ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request);

        return $banner;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inWebsite(Website $website, ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request);
        return $banner;
    }


    public function htmlResponse(ContentBlock $banner, ActionRequest $request): Response
    {

        return Inertia::render(
            'Portfolio/BannerWorkshop',
            [
                'title'       => __("Banner's workshop"),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($banner, $request),
                    'next'     => $this->getNext($banner, $request),
                ],
                'pageHead'    => [

                    'title'    => __('Workshop'),
                    'container'=> [
                        'icon'    => ['fal', 'fa-window-maximize'],
                        'tooltip' => __('Banner'),
                        'label'   => Str::possessive($banner->name)
                    ],
                    'iconRight'    =>
                        [
                            'icon'  => ['fal', 'drafting-compass'],
                            'title' => __("Banner's workshop")
                        ],

                    'actions' => [
                        [
                            'type'       => 'button',
                            'style'      => 'exitEdit',
                            'label'      => __('Exit workshop'),
                            'route'      => [
                                'name'       => preg_replace('/workshop$/', 'show', $this->routeName),
                                'parameters' => array_values($this->originalParameters),
                            ]
                        ]
                    ],
                ],
                'bannerLayout'=> $banner->layout,
                'updateRoute' => [
                    'name'       => 'models.content-block.update',
                    'parameters' => $banner->slug
                ],


            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (string $type, ContentBlock $banner, array $routeParameters, string $suffix) {
            return [
                [

                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('banners')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $banner->name,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $banner->name
                    ],


                    'suffix' => $suffix

                ],
            ];
        };


        return match ($routeName) {
            'web.banners.workshop' =>

            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['banner'],
                    [
                        'index' => [
                            'name'       => 'web.banners.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'web.banners.show',
                            'parameters' => [$routeParameters['banner']->slug]
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
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
            'web.banners.workshop' => [
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
