<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CaaS\Banners\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\InertiaAction;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Actions\UI\Customer\Banners\ShowBannersDashboard;
use App\Enums\UI\Customer\BannerTabsEnum;
use App\Http\Resources\History\CustomerHistoryResource;
use App\Http\Resources\Portfolio\BannerResource;
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
    use WithActionButtons;

    private Customer|Shop|PortfolioWebsite|Organisation|null $parent = null;

    public function handle(Banner $banner): Banner
    {
        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('catalogue.caas.edit');

        return $request->user()->hasPermissionTo('catalogue.caas.view');
    }

    public function asController(Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request)->withTab(BannerTabsEnum::values());

        return $this->handle($banner);
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
            'Catalogue/CaaS/Banner',
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
                    'title'       => $banner->name,
                    'icon'        => [
                        'tooltip' => __('banner'),
                        'icon'    => 'fal fa-sign'
                    ],
                    'container'   => $container,
                    'iconRight'   => $banner->state->stateIcon()[$banner->state->value],
                    'actions'     => [

                        /*
                        [
                            'type'  => 'button',
                            'style' => 'tertiary',
                            'label' => __('clone this banner'),
                            'icon'  => ["fal", "fa-paste"],
                            'route' => [
                                'name'       => 'customer.banners.duplicate',
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ],
                        */
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

                BannerTabsEnum::CHANGELOG->value => $this->tab == BannerTabsEnum::CHANGELOG->value
                    ?
                    fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: $banner,
                            prefix: BannerTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: $banner,
                            prefix: BannerTabsEnum::CHANGELOG->value
                        )
                    )),

            ]
        )->table(
            IndexCustomerHistory::make()->tableStructure(
                prefix: BannerTabsEnum::CHANGELOG->value
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
            'customer.banners.show',
            'customer.banners.edit' =>
            array_merge(
                ShowBannersDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    Banner::firstWhere('slug', $routeParameters['banner']),
                    [
                        'index' => [
                            'name'       => 'customer.banners.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.banners.show',
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
            'org.caas.banners.show',
            'org.caas.banners.edit' => [
                'label' => $banner->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'banner' => $banner->slug
                    ]
                ]
            ],
        };
    }

}
