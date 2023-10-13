<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\Helpers\Snapshot\UI\IndexSnapshots;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioWebsite\UI\ShowPortfolioWebsite;
use App\Actions\UI\Customer\CaaS\ShowCaaSDashboard;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\UI\Customer\BannerTabsEnum;
use App\Http\Resources\History\CustomerHistoryResource;
use App\Http\Resources\Portfolio\BannerResource;
use App\Http\Resources\Portfolio\SnapshotResource;
use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Organisation\Organisation;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBanner extends InertiaAction
{
    private Customer|Shop|PortfolioWebsite|Organisation $parent;

    public function handle(Organisation|Shop|Customer|PortfolioWebsite $parent, Banner $banner): Banner
    {
        $this->parent = $parent;

        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');
        $this->canDelete = $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');

        return
            (
                $request->get('customerUser')->hasPermissionTo('portfolio.banners.view')
            );
    }

    public function asController(Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request)->withTab(BannerTabsEnum::values());

        return $this->handle(customer(), $banner);
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request)->withTab(BannerTabsEnum::values());

        return $this->handle($portfolioWebsite, $banner);
    }

    public function htmlResponse(Banner $banner, ActionRequest $request): Response
    {
        $customer = $request->get('customer');

        $container = null;
        if (class_basename($this->parent) == 'PortfolioWebsite') {
            $container = [
                'icon'    => ['fal', 'fa-globe'],
                'tooltip' => __('Website'),
                'label'   => Str::possessive($this->parent->name)
            ];
        }


        return Inertia::render(
            'Banners/Banner',
            [
                'breadcrumbs'                   => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'                    => [
                    'previous' => $this->getPrevious($banner, $request),
                    'next'     => $this->getNext($banner, $request),
                ],
                'title'                         => $banner->name,
                'pageHead'                      => [
                    'title'     => $banner->name,
                    'icon'      => [
                        'tooltip' => __('banner'),
                        'icon'    => 'fal fa-rectangle-wide'
                    ],
                    'container' => $container,
                    'iconRight' =>
                        match ($banner->state) {
                            BannerStateEnum::LIVE => [

                                'tooltip' => __('live'),
                                'icon'    => 'fal fa-broadcast-tower',
                                'class'   => 'text-green-600'

                            ],
                            BannerStateEnum::UNPUBLISHED => [

                                'tooltip' => __('unpublished'),
                                'icon'    => 'fal fa-seedling'

                            ],
                            BannerStateEnum::RETIRED => [

                                'tooltip' => __('retired'),
                                'icon'    => 'fal fa-eye-slash'

                            ]
                        }

                    ,
                    'actions'   => [
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
                'tabs'                          => [
                    'current'    => $this->tab,
                    'navigation' => BannerTabsEnum::navigation()
                ],
                BannerTabsEnum::SHOWCASE->value => $this->tab == BannerTabsEnum::SHOWCASE->value
                    ?
                    fn () => BannerResource::make($banner)->getArray()
                    : Inertia::lazy(
                        fn () => BannerResource::make($banner)->getArray()
                    ),

                BannerTabsEnum::SNAPSHOTS->value => $this->tab == BannerTabsEnum::SNAPSHOTS->value
                    ?
                    fn () => SnapshotResource::collection(
                        IndexSnapshots::run(
                            parent: $banner,
                            prefix: BannerTabsEnum::SNAPSHOTS->value
                        )
                    )
                    : Inertia::lazy(fn () => SnapshotResource::collection(
                        IndexSnapshots::run(
                            parent: $banner,
                            prefix: BannerTabsEnum::SNAPSHOTS->value
                        )
                    )),

                BannerTabsEnum::CHANGELOG->value => $this->tab == BannerTabsEnum::CHANGELOG->value
                    ?
                    fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: $banner,
                            prefix:  BannerTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: $banner,
                            prefix:  BannerTabsEnum::CHANGELOG->value
                        )
                    )),

            ]
        )->table(
            IndexHistory::make()->tableStructure(
                parent: $banner,
                prefix:  BannerTabsEnum::CHANGELOG->value
            )
        )->table(
            IndexCustomerHistory::make()->tableStructure(
                prefix: BannerTabsEnum::SNAPSHOTS->value
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
                    'suffix'         => $suffix
                ],
            ];
        };


        return match ($routeName) {
            'customer.caas.banners.show',
            'customer.caas.banners.edit' =>
            array_merge(
                ShowCaaSDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    Banner::firstWhere('slug', $routeParameters['banner']),
                    [
                        'index' => [
                            'name'       => 'customer.portfolio.banners.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.portfolio.banners.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),
            'customer.portfolio.websites.show.banners.show',
            'customer.portfolio.websites.show.banners.edit' =>
            array_merge(
                ShowPortfolioWebsite::make()->getBreadcrumbs(
                    'customer.portfolio.websites.show',
                    $routeParameters
                ),
                $headCrumb(
                    'modelWithIndex',
                    Banner::firstWhere('slug', $routeParameters['banner']),
                    [
                        'index' => [
                            'name'       => 'customer.portfolio.websites.show.banners.index',
                            'parameters' => $routeParameters
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

    public function getPrevious(Banner $banner, ActionRequest $request): ?array
    {
        if (class_basename($this->parent) == 'PortfolioWebsite') {
            // todo, need to use a join
            $previous = null;
        } else {
            $previous = Banner::where('slug', '<', $banner->slug)->orderBy('slug')->first();
        }

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(Banner $banner, ActionRequest $request): ?array
    {
        if (class_basename($this->parent) == 'PortfolioWebsite') {
            // todo, need to use a join
            $next = null;
        } else {
            $next = Banner::where('slug', '>', $banner->slug)->orderBy('slug')->first();
        }


        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?Banner $banner, string $routeName): ?array
    {
        if (!$banner) {
            return null;
        }


        return match ($routeName) {
            'customer.caas.banners.show',
            'customer.caas.banners.edit' => [
                'label' => $banner->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'banner' => $banner->slug
                    ]
                ]
            ],
            'customer.portfolio.websites.show.banners.show',
            'customer.portfolio.websites.show.banners.edit' => [
                'label' => $banner->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'portfolioWebsite' => $this->parent->slug,
                        'banner'           => $banner->slug
                    ]
                ]
            ]
        };
    }

}
