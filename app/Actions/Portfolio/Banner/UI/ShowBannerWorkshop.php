<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Http\Resources\Portfolio\BannerResource;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBannerWorkshop extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->get('customerUser')->hasPermissionTo('portfolio.edit');
        $this->canDelete = $request->get('customerUser')->hasPermissionTo('portfolio.edit');

        return $request->get('customerUser')->hasPermissionTo("portfolio.view");
    }

    public function inCustomer(Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request);

        return $banner;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request);

        return $banner;
    }


    public function htmlResponse(Banner $banner, ActionRequest $request): Response
    {

        return Inertia::render(
            'Portfolio/BannerWorkshop',
            [
                'title'             => __("Banner's workshop"),
                'breadcrumbs'       => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation'        => [
                    'previous' => $this->getPrevious($banner, $request),
                    'next'     => $this->getNext($banner, $request),
                ],
                'pageHead'          => [

                    'title'     => __('Workshop'),
                    'container' => [
                        'icon'    => ['fal', 'fa-window-maximize'],
                        'tooltip' => __('Banner'),
                        'label'   => Str::possessive($banner->name)
                    ],
                    'iconRight' =>
                        [
                            'icon'  => ['fal', 'drafting-compass'],
                            'title' => __("Banner's workshop")
                        ],

                    'actionActualMethod' => 'patch',
                    'actions'            => [
                        [
                            'type'  => 'button',
                            'style' => 'exit',
                            'label' => __('Exit workshop'),
                            'route' => [
                                'name'       => preg_replace('/workshop$/', 'show', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters()),
                            ]
                        ],
                        [
                            'type'   => 'button',
                            'style'  => 'save',
                            'label'  => __('Publish'),
                            'icon'   => 'far fa-rocket-launch',
                            'route'  => [
                                'name'       => 'models.banner.publish',
                                'parameters' => [
                                    'banner' => $banner->slug
                                ]
                            ],
                            'method' => 'post',

                        ]
                    ],
                ],
                'firebase'          => true,
                'bannerLayout'      => $banner->compiled_layout,
                'banner'            => BannerResource::make($banner)->getArray(),
                'autoSaveRoute'     => [
                    'name'       => 'models.banner.fetch-firebase',
                    'parameters' => [
                        'banner' => $banner->slug
                    ]
                ],
                'imagesUploadRoute' => [
                    'name'       => $request->route()->getName().'.images.store',
                    'parameters' => $request->route()->originalParameters()
                ],

            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return ShowBanner::make()->getBreadcrumbs(
            preg_replace('/workshop$/', 'show', $routeName),
            $routeParameters,
            '('.__('Workshop').')'
        );
    }

    public function getPrevious(Banner $banner, ActionRequest $request): ?array
    {
        $previous = Banner::where('code', '<', $banner->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName(), $request->route()->parameters);
    }

    public function getNext(Banner $banner, ActionRequest $request): ?array
    {
        $next = Banner::where('code', '>', $banner->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName(), $request->route()->parameters);
    }

    private function getNavigation(?Banner $banner, string $routeName, array $routeParameters): ?array
    {
        if (!$banner) {
            return null;
        }

        return match ($routeName) {
            'customer.portfolio.banners.workshop', 'customer.portfolio.websites.show.banners.workshop' => [
                'label' => $banner->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => $routeParameters
                ]
            ],
        };
    }

}
