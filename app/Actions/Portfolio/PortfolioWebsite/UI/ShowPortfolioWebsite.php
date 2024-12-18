<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\Banner\UI\IndexBanners;
use App\Actions\Portfolio\PortfolioWebsite\Traits\HasPortfolioWebsiteSubNavigation;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Actions\Traits\WelcomeWidgets\WithFirstBanner;
use App\Actions\UI\Customer\Portfolio\ShowPortfolio;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Customer\PortfolioWebsiteTabsEnum;
use App\Http\Resources\History\CustomerHistoryResource;
use App\Http\Resources\Portfolio\BannersResource;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowPortfolioWebsite extends InertiaAction
{
    use AsAction;
    use WithInertia;
    use WithFirstBanner;
    use WithActionButtons;
    use HasPortfolioWebsiteSubNavigation;


    private Customer|Banner $parent;

    public function handle(Customer|Banner $parent, PortfolioWebsite $portfolioWebsite): PortfolioWebsite
    {
        $this->parent = $parent;

        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit       = $request->get('customerUser')->hasPermissionTo('portfolio.edit');
        $this->canDelete     = $request->get('customerUser')->hasPermissionTo('portfolio.edit');
        $this->canEditFooter = $request->get('customerUser')->hasPermissionTo('portfolio.footer.edit');

        return $request->get('customerUser')->hasAnyPermission(['portfolio.view', 'portfolio.footer.view', 'portfolio.banners.view']);
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $this->initialisation($request)->withTab(PortfolioWebsiteTabsEnum::values());

        return $this->handle(parent: $request->get('customer'), portfolioWebsite: $portfolioWebsite);
    }


    public function htmlResponse(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
    {
        $customer      = $request->get('customer');
        $subNavigation = $this->getSubNavigation($request);
        $firstBanners  = $this->canEdit ? $this->getFirstBannerWidget($portfolioWebsite) : null;

        $inertia = Inertia::render(
            'Portfolio/PortfolioWebsite',
            [
                'title'          => __('PortfolioWebsite'),
                'breadcrumbs'    => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->originalParameters()
                ),
                'navigation'     => [
                    'previous' => $this->getPrevious($portfolioWebsite, $request),
                    'next'     => $this->getNext($portfolioWebsite, $request),
                ],
                'pageHead'       => [
                    'title'       => $portfolioWebsite->name,
                    'icon'        => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions' => [
                        $this->canEditFooter ? [
                            'type'    => 'button',
                            'style'   => 'primary',
                            'label'   => __('Footer'),
                            'icon'    => 'fal fa-shoe-prints',
                            'tooltip' => __('Footer Workshop'),
                            'route'   => [
                                'name'       => 'customer.portfolio.websites.footer',
                                'parameters' => array_merge(
                                    $request->route()->originalParameters()
                                )
                            ]
                        ] : null,
                        $this->canDelete ? $this->getDeleteActionIcon($request) : null,
                        $this->canEdit ? $this->getEditActionIcon($request) : null,
                    ],
                    'subNavigation'    => $subNavigation,
                ],
                'tabs'           => [
                    'current'    => $this->tab,
                    'navigation' => PortfolioWebsiteTabsEnum::navigation()
                ],
                'hasFirstBanner' => !is_null($firstBanners),

                PortfolioWebsiteTabsEnum::CHANGELOG->value => $this->tab == PortfolioWebsiteTabsEnum::CHANGELOG->value
                    ?
                    fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: PortfolioWebsite::class,
                            prefix: PortfolioWebsiteTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: PortfolioWebsite::class,
                            prefix: PortfolioWebsiteTabsEnum::CHANGELOG->value
                        )
                    )),

                PortfolioWebsiteTabsEnum::BANNERS->value => $this->tab == PortfolioWebsiteTabsEnum::BANNERS->value
                    ?
                    fn () => $firstBanners ?: BannersResource::collection(IndexBanners::run($portfolioWebsite, PortfolioWebsiteTabsEnum::BANNERS->value))
                    : Inertia::lazy(
                        fn () => $firstBanners ?: BannersResource::collection(IndexBanners::run($portfolioWebsite, PortfolioWebsiteTabsEnum::BANNERS->value))
                    )
            ]
        )
            ->table(
                IndexCustomerHistory::make()->tableStructure(
                    prefix: PortfolioWebsiteTabsEnum::CHANGELOG->value
                )
            );

        if (!$firstBanners) {
            $inertia->table(
                IndexBanners::make()->tableStructure(
                    parent: $portfolioWebsite,
                    prefix: PortfolioWebsiteTabsEnum::BANNERS->value
                )
            );
        }

        return $inertia;
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
                ShowPortfolio::make()->getBreadcrumbs(),
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
