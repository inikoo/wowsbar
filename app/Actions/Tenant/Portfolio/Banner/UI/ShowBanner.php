<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 11:21:24 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\Tenant\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolioDashboard;
use App\Enums\UI\Tenant\BannerTabsEnum;
use App\Enums\UI\Tenant\PortfolioWebsiteTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBanner extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->can('portfolio.edit');
        $this->canDelete = $request->user()->can('portfolio.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.view')
            );
    }

    public function inTenant(Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request)->withTab(\App\Enums\UI\Tenant\BannerTabsEnum::values());

        return $banner;
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, Banner $banner, ActionRequest $request): Banner
    {

        $this->initialisation($request)->withTab(BannerTabsEnum::values());

        return $banner;
    }


    public function htmlResponse(Banner $banner, ActionRequest $request): Response
    {

        return Inertia::render(
            'Tenant/Portfolio/Banner',
            [
                'breadcrumbs'                    => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'                          => $banner->code,
                'pageHead'                       => [
                    'title'   => $banner->name,
                    'icon'    => [
                        'title' => __('banner'),
                        'icon'  => 'fal fa-window-maximize'
                    ],
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('workshop'),
                            'icon'  => ["fal", "fa-drafting-compass"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'workshop', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                        $this->canDelete ? [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'remove', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false
                    ],
                ],
                'tabs'                                                         => [
                    'current'    => $this->tab,
                    'navigation' => \App\Enums\UI\Tenant\BannerTabsEnum::navigation()
                ],
                \App\Enums\UI\Tenant\PortfolioWebsiteTabsEnum::SHOWCASE->value => $this->tab == \App\Enums\UI\Tenant\PortfolioWebsiteTabsEnum::SHOWCASE->value ?
                    fn () => $banner->compiledLayout()
                    : Inertia::lazy(fn () => $banner->compiledLayout()),

                \App\Enums\UI\Tenant\PortfolioWebsiteTabsEnum::CHANGELOG->value => $this->tab == PortfolioWebsiteTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($banner))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($banner)))

            ]
        )->table(IndexHistories::make()->tableStructure());
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = null): array
    {
        $headCrumb = function (string $type, Banner $banner, array $routeParameters, string $suffix = null) {
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
            'portfolio.banners.show' =>
            array_merge(
                ShowPortfolioDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['banner'],
                    [
                        'index' => [
                            'name'       => 'portfolio.banners.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'portfolio.banners.show',
                            'parameters' => [$routeParameters['banner']->slug]
                        ]
                    ],
                    $suffix
                ),
            ),
            'portfolio.portfolio-websites.show.banners.show' =>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'portfolio.portfolio-websites.show',
                    ['portfolioWebsite' => $routeParameters['portfolioWebsite']]
                ),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['banner'],
                    [
                        'index' => [
                            'name'       => 'portfolio.portfolio-websites.show.banners.index',
                            'parameters' => [$routeParameters['portfolioWebsite']->slug]
                        ],
                        'model' => [
                            'name'       => 'portfolio.portfolio-websites.show.banners.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }
}
