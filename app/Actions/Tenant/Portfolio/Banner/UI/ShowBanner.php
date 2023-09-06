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
use App\Actions\Tenant\Portfolio\Snapshot\UI\IndexSnapshots;
use App\Actions\UI\Tenant\Portfolio\ShowPortfolio;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\UI\Tenant\BannerTabsEnum;
use App\Enums\UI\Tenant\PortfolioWebsiteTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\SnapshotResource;
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
                'banner'                         => $banner->only(['slug', 'ulid', 'id', 'code', 'name','state']),
                'pageHead'                       => [
                    'title'   => $banner->name,
                    'icon'    => [
                        'tooltip' => __('banner'),
                        'icon'    => 'fal fa-window-maximize'
                    ],
                    'iconRight'    =>
                        match($banner->state) {
                            BannerStateEnum::LIVE=> [

                                    'tooltip' => __('live'),
                                    'icon'    => 'fal fa-broadcast-tower',
                                    'class'   => 'text-green-600'

                            ],
                            BannerStateEnum::UNPUBLISHED=> [

                                    'tooltip' => __('unpublished'),
                                    'icon'    => 'fal fa-seedling'

                            ],
                            BannerStateEnum::RETIRED=> [

                                    'tooltip' => __('retired'),
                                    'icon'    => 'fal fa-eye-slash'

                            ]
                        }

                       ,
                    'actions' => [
                        $this->canDelete ? [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'remove', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'tertiary',
                            'label' => __('edit'),
                            'icon'  => ["fal", "fa-pencil"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                        [
                            'type'  => 'button',
                            'style' => 'secondary',
                            'label' => __('clone this banner'),
                            'icon'  => ["fal", "fa-paste"],
                            'route' => [
                                'name'       => 'portfolio.banners.duplicate',
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ],
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'primary',
                            'label' => __('workshop'),
                            'icon'  => ["fal", "fa-drafting-compass"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'workshop', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
                    ],
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => BannerTabsEnum::navigation()
                ],
                PortfolioWebsiteTabsEnum::SHOWCASE->value => $this->tab == PortfolioWebsiteTabsEnum::SHOWCASE->value ?
                    fn () => [
                        'banner'=> $banner->compiled_layout,
                        'url'   => 'xxx'
                    ]
                    : Inertia::lazy(
                        fn () =>
                    [
                        'banner'=> $banner->compiled_layout,
                        'url'   => 'xxx'
                    ]
                    ),

                BannerTabsEnum::SNAPSHOTS->value => $this->tab == BannerTabsEnum::SNAPSHOTS->value ?
                    fn () => SnapshotResource::collection(IndexSnapshots::run($banner))
                    : Inertia::lazy(fn () => SnapshotResource::collection(IndexSnapshots::run($banner))),

                BannerTabsEnum::CHANGELOG->value => $this->tab == BannerTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($banner))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($banner))),

            ]
        )->table(IndexHistories::make()->tableStructure(
            exportLinks: [
                'export' => [
                    'route' => [
                        'name' => 'export.histories.index'
                    ]
                ]
            ]
        ))->table(IndexSnapshots::make()->tableStructure(null, 'sht',
                exportLinks: [
                'export' => [
                    'route' => [
                        'name' => 'export.snapshots.index'
                    ]
                ]
            ]
        ));
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
            'portfolio.banners.show',
            'portfolio.banners.edit' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
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
            'portfolio.websites.show.banners.show' =>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'portfolio.websites.show',
                    ['portfolioWebsite' => $routeParameters['portfolioWebsite']]
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
                            'name'       => 'portfolio.websites.show.banners.show',
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
