<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 11:21:24 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use App\Actions\Elasticsearch\History\IndexHistories;
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

class ShowDeletedBanner extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        $this->canRestore   = $request->user()->can('portfolio.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->can('portfolio.view')
            );
    }

    public function inTenant(Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request)->withTab(BannerTabsEnum::values());

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
                        $this->canRestore ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('restore'),
                            'icon'  => ["fal", "fa-trash-restore-alt"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'workshop', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                    ],
                ],
                'tabs'                           => [
                    'current'    => $this->tab,
                    'navigation' => BannerTabsEnum::navigation()
                ],
                \App\Enums\UI\Tenant\PortfolioWebsiteTabsEnum::SHOWCASE->value => $this->tab == PortfolioWebsiteTabsEnum::SHOWCASE->value ?
                    fn () => $banner->compiled_layout
                    : Inertia::lazy(fn () => $banner->compiled_layout),

                PortfolioWebsiteTabsEnum::CHANGELOG->value => $this->tab == PortfolioWebsiteTabsEnum::CHANGELOG->value ?
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
            'portfolio.banners.deleted' =>
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
                            'name'       => 'portfolio.banners.deleted',
                            'parameters' => [$routeParameters['banner']->slug]
                        ]
                    ],
                    $suffix
                ),
            ),
            'portfolio.websites.show.banners.deleted' =>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'portfolio.websites.show',
                    ['website' => $routeParameters['portfolioWebsite']]
                ),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['banner'],
                    [
                        'index' => [
                            'name'       => 'portfolio.websites.show.banners.index',
                            'parameters' => [$routeParameters['portfolioWebsite']->slug]
                        ],
                        'model' => [
                            'name'       => 'portfolio.websites.show.banners.deleted',
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
