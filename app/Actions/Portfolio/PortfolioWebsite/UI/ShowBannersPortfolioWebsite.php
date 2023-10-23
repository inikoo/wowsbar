<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 23 Oct 2023 20:21:42 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\Helpers\History\IndexCustomerHistory;
use App\Actions\InertiaAction;
use App\Actions\Portfolio\Banner\UI\IndexBanners;
use App\Actions\Traits\Actions\WithActionButtons;
use App\Actions\Traits\WelcomeWidgets\WithFirstBanner;
use App\Actions\Traits\WithPortfolioWebsite;
use App\Actions\UI\WithInertia;
use App\Enums\UI\Customer\BannersPortfolioWebsiteTabsEnum;
use App\Http\Resources\History\CustomerHistoryResource;
use App\Http\Resources\Portfolio\BannersResource;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowBannersPortfolioWebsite extends InertiaAction
{
    use AsAction;
    use WithInertia;
    use WithFirstBanner;
    use WithActionButtons;
    use WithPortfolioWebsite;


    private bool $canPortfolio;

    public function handle(PortfolioWebsite $portfolioWebsite): PortfolioWebsite
    {
        return $portfolioWebsite;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit      = $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');
        $this->canDelete    = $request->get('customerUser')->hasPermissionTo('portfolio.banners.edit');
        $this->canPortfolio = $request->get('customerUser')->hasPermissionTo('portfolio');

        return $request->get('customerUser')->hasPermissionTo('portfolio.banners.view');
    }

    public function asController(Banner $banner, PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        $this->parent = $banner;
        $this->initialisation($request)->withTab(BannersPortfolioWebsiteTabsEnum::values());

        return $this->handle(portfolioWebsite: $portfolioWebsite);
    }


    public function htmlResponse(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
    {
        $customer = $request->get('customer');

        $firstBanners = $this->canEdit ? $this->getFirstBannerWidget($portfolioWebsite) : null;

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
                    'title'   => $portfolioWebsite->name,
                    'icon'    => [
                        'title' => __('website'),
                        'icon'  => 'fal fa-globe'
                    ],
                    'actions' => [
                        $this->canPortfolio ? [
                            'type'  => 'button',
                            'style' => 'primary',
                            'label' => __('Website view'),
                            'route' => [
                                'name'       => 'customer.portfolio.websites.show',
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ] : []
                    ]

                ],
                'tabs'           => [
                    'current'    => $this->tab,
                    'navigation' => BannersPortfolioWebsiteTabsEnum::navigation()
                ],
                'hasFirstBanner' => !is_null($firstBanners),

                BannersPortfolioWebsiteTabsEnum::CHANGELOG->value => $this->tab == BannersPortfolioWebsiteTabsEnum::CHANGELOG->value
                    ?
                    fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: PortfolioWebsite::class,
                            prefix: BannersPortfolioWebsiteTabsEnum::CHANGELOG->value
                        )
                    )
                    : Inertia::lazy(fn () => CustomerHistoryResource::collection(
                        IndexCustomerHistory::run(
                            customer: $customer,
                            model: PortfolioWebsite::class,
                            prefix: BannersPortfolioWebsiteTabsEnum::CHANGELOG->value
                        )
                    )),

                BannersPortfolioWebsiteTabsEnum::BANNERS->value => $this->tab == BannersPortfolioWebsiteTabsEnum::BANNERS->value
                    ?
                    fn () => $firstBanners ?: BannersResource::collection(IndexBanners::run($portfolioWebsite, BannersPortfolioWebsiteTabsEnum::BANNERS->value))
                    : Inertia::lazy(
                        fn () => $firstBanners ?: BannersResource::collection(IndexBanners::run($portfolioWebsite, BannersPortfolioWebsiteTabsEnum::BANNERS->value))
                    )
            ]
        )
            ->table(
                IndexCustomerHistory::make()->tableStructure(
                    prefix: BannersPortfolioWebsiteTabsEnum::CHANGELOG->value
                )
            );


        if (!$firstBanners) {
            $inertia->table(
                IndexBanners::make()->tableStructure(
                    parent: $portfolioWebsite,
                    prefix: BannersPortfolioWebsiteTabsEnum::BANNERS->value
                )
            );
        }

        return $inertia;
    }


}
