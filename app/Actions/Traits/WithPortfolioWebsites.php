<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 23 Oct 2023 19:29:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Actions\Helpers\History\IndexHistory;
use App\Enums\UI\Customer\PortfolioWebsitesTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Http\Resources\Portfolio\PortfolioWebsiteResource;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Lorisleiva\Actions\ActionRequest;
use Spatie\QueryBuilder\AllowedFilter;

trait WithPortfolioWebsites
{
    public function getGlobalSearch(): AllowedFilter
    {
        return AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->whereAnyWordStartWith('portfolio_websites.name', $value)
                    ->orWhere('portfolio_websites.url', 'ilike', "%$value%")
                    ->orWhere('portfolio_websites.slug', 'ilike', "$value%")
                    ->orWhere('portfolio_websites.name', 'ilike', "$value%");
            });
        });
    }

    public function getHtmlPrompts(string $scope, ActionRequest $request, LengthAwarePaginator $portfolioWebsites): array
    {
        $title     = __('websites');
        $pageTitle = __('websites');
        $icon      = null;

        if ($scope == 'banners') {
            $title     = __('websites (banners)');
            $pageTitle = __('websites (banners)');
            $icon      = [
                'title' => __('Banners'),
                'icon'  => 'fal fa-sign'
            ];
        }

        return [
            'breadcrumbs' => $this->getBreadcrumbs(
                $request->route()->getName()
            ),
            'title'       => $title,
            'pageHead'    => [
                'title'     => $pageTitle,
                'icon'      => $icon,
                'iconRight' => [
                    'title' => __('website'),
                    'icon'  => 'fal fa-globe'
                ],
            ],
            'tabs'        => [
                'current'    => $this->tab,
                'navigation' => PortfolioWebsitesTabsEnum::navigation()
            ],

            PortfolioWebsitesTabsEnum::WEBSITES->value => $this->tab == PortfolioWebsitesTabsEnum::WEBSITES->value ?
                fn () => PortfolioWebsiteResource::collection($portfolioWebsites)
                : Inertia::lazy(fn () => PortfolioWebsiteResource::collection($portfolioWebsites)),

            PortfolioWebsitesTabsEnum::CHANGELOG->value => $this->tab == PortfolioWebsitesTabsEnum::CHANGELOG->value ?
                fn () => HistoryResource::collection(IndexHistory::run(PortfolioWebsite::class))
                : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run(PortfolioWebsite::class)))
        ];
    }

}
