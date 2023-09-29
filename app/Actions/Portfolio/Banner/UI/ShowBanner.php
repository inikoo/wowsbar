<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\UI\Customer\BannerTabsEnum;
use App\Enums\UI\Customer\PortfolioWebsiteTabsEnum;
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
        $this->canEdit   = $request->user()->hasPermissionTo('portfolio.edit');
        $this->canDelete = $request->user()->hasPermissionTo('portfolio.edit');

        return
            (
                $request->user()->tokenCan('root') or
                $request->user()->hasPermissionTo('portfolio.view')
            );
    }

    public function inCustomer(Banner $banner, ActionRequest $request): Banner
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
            'Portfolio/Banner',
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
                        [
                            'type'  => 'button',
                            'style' => 'tertiary',
                            'label' => __('clone this banner'),
                            'icon'  => ["fal", "fa-paste"],
                            'route' => [
                                'name'       => 'customer.portfolio.banners.duplicate',
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ],
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'secondary',
                            'label' => __('edit'),
                            'icon'  => ["fal", "fa-pencil"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : false,
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
                    fn () => SnapshotResource::collection(\App\Actions\Portfolio\Snapshot\UI\IndexSnapshots::run($banner))
                    : Inertia::lazy(fn () => SnapshotResource::collection(\App\Actions\Portfolio\Snapshot\UI\IndexSnapshots::run($banner))),

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
        ))->table(
            \App\Actions\Portfolio\Snapshot\UI\IndexSnapshots::make()->tableStructure(
                null,
                'sht',
                exportLinks: [
                'export' => [
                    'route' => [
                        'name' => 'export.snapshots.index'
                    ]
                ]
            ]
            )
        );
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
            'customer.portfolio.banners.show',
            'customer.portfolio.banners.edit' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['banner'],
                    [
                        'index' => [
                            'name'       => 'customer.portfolio.banners.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.portfolio.banners.show',
                            'parameters' => [$routeParameters['banner']->slug]
                        ]
                    ],
                    $suffix
                ),
            ),
            'customer.portfolio.websites.show.banners.show',
            'customer.portfolio.websites.show.banners.edit'=>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'customer.portfolio.websites.show',
                    ['portfolioWebsite' => $routeParameters['portfolioWebsite']]
                ),
                $headCrumb(
                    'modelWithIndex',
                    $routeParameters['banner'],
                    [
                        'index' => [
                            'name'       => 'customer.portfolio.websites.show.banners.index',
                            'parameters' => [$routeParameters['portfolioWebsite']->slug]
                        ],
                        'model' => [
                            'name'       => 'customer.portfolio.websites.show.banners.show',
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
