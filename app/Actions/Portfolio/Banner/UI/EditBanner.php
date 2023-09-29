<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsite;
use Exception;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditBanner extends InertiaAction
{
    public function handle(Banner $banner): Banner
    {
        return $banner;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()>hasPermissionTo('portfolio.edit');
        return $request->user()>hasPermissionTo("portfolio.edit");

    }

    public function asController(PortfolioWebsite $portfolioWebsite, Banner $banner, ActionRequest $request): Banner
    {
        $this->initialisation($request);
        return $this->handle($banner);
    }

    /**
     * @throws Exception
     */
    public function htmlResponse(Banner $banner, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                    'title'       => __("Website's settings"),
                    'breadcrumbs' => $this->getBreadcrumbs(
                        $request->route()->getName(),
                        $request->route()->parameters()
                    ),
                    'navigation'   => [
                        'previous' => $this->getPrevious($banner, $request),
                        'next'     => $this->getNext($banner, $request),
                    ],
                    'pageHead'    => [
                        'title'     => __('Edit banner'),
                        'container' => [
                            'icon'    => ['fal', 'fa-globe'],
                            'tooltip' => __('Website'),
                            'label'   => Str::possessive($banner->name)
                        ],

                        'iconRight'    =>
                            [
                                'icon'  => ['fal', 'fa-edit'],
                                'title' => __("Editing banner")
                            ],

                        'actions'   => [
                            [
                                'type'  => 'button',
                                'style' => 'exit',
                                'label' => __('Exit edit'),
                                'route' => [
                                    'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                                    'parameters' => array_values($request->route()->originalParameters())
                                ]
                            ]
                        ],
                    ],
                    'formData' => [
                        'blueprint' => [
                            [
                                'title'  => __('ID/domain'),
                                'icon'   => 'fa-light fa-id-card',
                                'fields' => [
                                    'code' => [
                                        'type'     => 'input',
                                        'label'    => __('code'),
                                        'value'    => $banner->code,
                                        'required' => true,
                                    ],
                                    'name' => [
                                        'type'     => 'input',
                                        'label'    => __('name'),
                                        'value'    => $banner->name,
                                        'required' => true,
                                    ],
                                    'portfolio_website_id' => [
                                        'type'     => 'select',
                                        'label'    => __('website'),
                                        'value'    => $banner->portfolio_website_id,
                                        'options'  => \App\Actions\Portfolio\PortfolioWebsite\UI\GetPortfolioWebsitesOptions::run()
                                    ],
                                ]
                            ],

                    ],
                        'args'      => [
                            'updateRoute' => [
                                'name'       => 'models.banner.update',
                                'parameters' => $banner->slug
                            ],
                        ]
                    ],

                ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {

        return ShowBanner::make()->getBreadcrumbs(
            $routeName,
            $routeParameters,
            suffix: '('.__('editing').')'
        );
    }

    public function getPrevious(Banner $banner, ActionRequest $request): ?array
    {
        $previous = Banner::where('code', '<', $banner->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request);
    }

    public function getNext(Banner $banner, ActionRequest $request): ?array
    {
        $next = Banner::where('code', '>', $banner->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request);
    }

    private function getNavigation(?Banner $banner, ActionRequest $request): ?array
    {
        if (!$banner) {
            return null;
        }

        $routeName=$request->route()->getName();
        return match ($routeName) {
            'customer.portfolio.websites.show.banners.edit' => [
                'label' => $banner->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => $request->route()->originalParameters()
                ]
            ],
            default => null
        };
    }
}
