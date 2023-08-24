<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 11:21:24 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
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
        $this->canEdit   = $request->user()->can('portfolio.edit');
        $this->canDelete = $request->user()->can('portfolio.edit');

        return $request->user()->hasPermissionTo("portfolio.view");
    }

    public function inTenant(Banner $banner, ActionRequest $request): Banner
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
            'Tenant/Portfolio/BannerWorkshop',
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
                            'route'  => [
                                'name'       => 'models.banner.update',
                                'parameters' => [
                                    'banner' => $banner->slug
                                ]
                            ],
                            'method' => 'post',

                        ]
                    ],
                ],
                'firebase'          => true,
                'bannerLayout'      => $banner->compiledLayout(),
                'banner'            => $banner->only(['slug', 'ulid', 'id', 'code', 'name','state']),
                'imagesUploadRoute' => [
                    'name'      => $request->route()->getName().'.images.store',
                    'arguments' => $request->route()->originalParameters()
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
            'portfolio.banners.workshop', 'portfolio.websites.show.banners.workshop' => [
                'label' => $banner->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => $routeParameters
                ]
            ],
        };
    }

}
