<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 19:49:40 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\Helpers\History\IndexHistories;
use App\Actions\InertiaAction;
use App\Actions\UI\Dashboard\ShowDashboard;
use App\Enums\UI\BannerTabsEnum;
use App\Enums\UI\WebsiteTabsEnum;
use App\Http\Resources\History\HistoryResource;
use App\Models\Portfolio\ContentBlock;
use App\Models\Portfolio\Website;
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

    public function inTenant(ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request)->withTab(BannerTabsEnum::values());
        return $banner;
    }

    public function inWebsite(Website $website, ContentBlock $banner, ActionRequest $request): ContentBlock
    {
        $this->initialisation($request)->withTab(BannerTabsEnum::values());
        return $banner;
    }



    public function htmlResponse(ContentBlock $banner, ActionRequest $request): Response
    {

        return Inertia::render(
            'Portfolio/Banner',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title'       => $banner->code,
                'pageHead'    => [
                    'title'   => $banner->name,
                    'icon'    => [
                        'title' => __('banner'),
                        'icon'  => 'fal fa-window-maximize'
                    ],
                    'actions' => [
                        // $this->canEdit ? [
                        //     'type'  => 'button',
                        //     'style' => 'edit',
                        //     'label' => __('settings'),
                        //     'icon'  => ["fal", "fa-sliders-h"],
                        //     'route' => [
                        //         'name'       => preg_replace('/show$/', 'edit', $this->routeName),
                        //         'parameters' => array_values($this->originalParameters)
                        //     ]
                        // ] : false,
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'edit',
                            'label' => __('workshop'),
                            'icon'  => ["fal", "fa-drafting-compass"],
                            'route' => [
                                'name'       => preg_replace('/show$/', 'workshop', $this->routeName),
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : false,
                        /*$this->canDelete ? [
                            'type'  => 'button',
                            'style' => 'delete',
                            'route' => [
                                'name'       => 'websites.remove',
                                'parameters' => array_values($this->originalParameters)
                            ]
                        ] : false */
                    ],
                ],
                'tabs'        => [
                    'current'    => $this->tab,
                    'navigation' => BannerTabsEnum::navigation()
                ],
                WebsiteTabsEnum::SHOWCASE->value => $this->tab == WebsiteTabsEnum::SHOWCASE->value ?
                    fn () => $banner
                    : Inertia::lazy(fn () => $banner),

                WebsiteTabsEnum::CHANGELOG->value => $this->tab == WebsiteTabsEnum::CHANGELOG->value ?
                    fn () => HistoryResource::collection(IndexHistories::run($banner))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistories::run($banner)))

            ]
        )->table(IndexHistories::make()->tableStructure());
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $headCrumb = function (array $routeParameters = []) {
            return [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => $routeParameters,
                        'label' => __('banners'),
                        'icon'  => 'fal fa-bars'
                    ],
                ],
            ];
        };

        return match ($routeName) {
            'portfolio.banners.index' =>
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                $headCrumb(
                    [
                        'name' => 'portfolio.banners.index',
                        null
                    ]
                ),
            ),

            default => []
        };
    }
}
