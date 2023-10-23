<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 23 Oct 2023 20:33:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Actions\UI\Customer\Banners\ShowBannersDashboard;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\ActionRequest;

trait WithPortfolioWebsite
{
    private Banner|Customer $parent;


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
                            'label' => $portfolioWebsite->slug,
                        ],

                    ],
                    'simple'         => [
                        'route' => $routeParameters['model'],
                        'label' => $portfolioWebsite->slug
                    ],
                    'suffix'         => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'customer.banners.websites.show',
            'customer.banners.websites.edit' =>
            array_merge(
                ShowBannersDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    'modelWithIndex',
                    PortfolioWebsite::firstWhere('slug', $routeParameters['portfolioWebsite']),
                    [
                        'index' => [
                            'name'       => 'customer.banners.websites.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'customer.banners.websites.show',
                            'parameters' => $routeParameters
                        ]
                    ],
                    $suffix
                ),
            ),

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
        $previous = PortfolioWebsite::where('slug', '<', $portfolioWebsite->slug)->orderBy('slug', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PortfolioWebsite $portfolioWebsite, ActionRequest $request): ?array
    {
        $next = PortfolioWebsite::where('slug', '>', $portfolioWebsite->slug)->orderBy('slug')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PortfolioWebsite $portfolioWebsite, string $routeName): ?array
    {
        if (!$portfolioWebsite) {
            return null;
        }


        return match ($routeName) {
            'customer.banners.websites.show',
            'customer.banners.websites.edit' => [
                'label' => $portfolioWebsite->slug,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'banner'           => $this->parent->slug,
                        'portfolioWebsite' => $portfolioWebsite->slug
                    ]
                ]
            ],
            'customer.portfolio.websites.show',
            'customer.portfolio.websites.edit' => [
                'label' => $portfolioWebsite->slug,
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
