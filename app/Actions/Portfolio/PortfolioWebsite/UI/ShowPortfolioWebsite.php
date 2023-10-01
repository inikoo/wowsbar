<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\Banner\UI\IndexBanners;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Customer\PortfolioWebsiteTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\BannerResource;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowPortfolioWebsite extends InertiaAction
{
    use AsAction;
    use WithInertia;


    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->get('customerUser')->hasPermissionTo('portfolio.edit');
        $this->canDelete = $request->get('customerUser')->hasPermissionTo('portfolio.edit');

        return $request->get('customerUser')->hasPermissionTo('portfolio.view');
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $this->initialisation($request)->withTab(PortfolioWebsiteTabsEnum::values());

        return $portfolioWebsite;
    }

    public function htmlResponse(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
    {
        return Inertia::render(
            'Portfolio/PortfolioWebsite',
            [
                'title'       => __('PortfolioWebsite'),
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'  => [
                    'previous' => $this->getPrevious($portfolioWebsite, $request),
                    'next'     => $this->getNext($portfolioWebsite, $request),
                ],
                'pageHead'    => [
                    'title'   => $portfolioWebsite->name,
                    'icon'    => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions' => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'route' => [
                                'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]
                        ] : null
                    ]
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioWebsiteTabsEnum::navigation()
                ],

                PortfolioWebsiteTabsEnum::CHANGELOG->value => $this->tab == PortfolioWebsiteTabsEnum::CHANGELOG->value ?
                    fn() => HistoryResource::collection(IndexHistories::run($portfolioWebsite))
                    : Inertia::lazy(fn() => HistoryResource::collection(IndexHistories::run($portfolioWebsite))),

                PortfolioWebsiteTabsEnum::BANNERS->value => $this->tab == PortfolioWebsiteTabsEnum::BANNERS->value ?
                    fn() => BannerResource::collection(IndexBanners::run($portfolioWebsite, 'banners'))
                    : Inertia::lazy(fn() => BannerResource::collection(IndexBanners::run($portfolioWebsite, 'banners')))
            ]
        )
            ->table(IndexBanners::make()->tableStructure(
                parent:$portfolioWebsite,prefix: 'banners'
            ))
            ->table(IndexHistories::make()->tableStructure());
    }

    public function jsonResponse(PortfolioWebsite $portfolioWebsite): PortfolioWebsiteResource
    {
        return new PortfolioWebsiteResource($portfolioWebsite);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (string $type, PortfolioWebsite $portfolioWebsite, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => $type,
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('websites')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $portfolioWebsite->code,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $portfolioWebsite->code
                    ],
                    'suffix'         => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'customer.portfolio.websites.show',
            'customer.portfolio.websites.edit' =>
            array_merge(
                ShowPortfolio::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    PortfolioWebsite::firstWhere('slug', $routeParameters['portfolioWebsite']),
                    [
                        'index' => [
                            'name'       => 'customer.portfolio.websites.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.portfolio.websites.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

            default => []
        };
    }

    public function getPrevious(PortfolioWebsite $portfolioWebsite, ActionRequest $request): ?array
    {
        $previous = PortfolioWebsite::where('code', '<', $portfolioWebsite->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PortfolioWebsite $portfolioWebsite, ActionRequest $request): ?array
    {
        $next = PortfolioWebsite::where('code', '>', $portfolioWebsite->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PortfolioWebsite $portfolioWebsite, string $routeName): ?array
    {
        if (!$portfolioWebsite) {
            return null;
        }


        return match ($routeName) {
            'customer.portfolio.websites.show',
            'customer.portfolio.websites.edit' => [
                'label' => $portfolioWebsite->code,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'portfolioWebsite' => $portfolioWebsite->slug
                    ]
                ]
            ]
        };
    }
}
