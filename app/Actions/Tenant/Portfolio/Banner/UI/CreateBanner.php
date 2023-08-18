<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 11:21:24 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateBanner extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('portfolio.edit');
    }


    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle($portfolioWebsite, $request);
    }


    public function handle(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
    {
        return Inertia::render(
            'Tenant/CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->parameters()
                ),
                'title'       => __('new banner'),
                'pageHead'    => [
                    'title'   => __('banner'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' =>
                                match ($request->route()->getName()) {
                                    'portfolio.portfolio-websites.show.banners.create' =>
                                    [
                                        'name'       => 'portfolio.portfolio-websites.show',
                                        'parameters' => array_values($request->route()->originalParameters())
                                    ],
                                    default => [
                                        'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                        'parameters' => array_values($request->route()->originalParameters())
                                    ]
                                }


                        ]
                    ]


                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('ID/name'),
                            'fields' => [

                                'code' => [
                                    'type'     => 'input',
                                    'label'    => __('code'),
                                    'required' => true,
                                ],
                                'name' => [
                                    'type'     => 'input',
                                    'label'    => __('name'),
                                    'required' => true,
                                    'value'    => '',
                                ],
                            ]
                        ],


                    ],
                    'route'     => [
                        'name'      => 'models.portfolio-website.banner.store',
                        'arguments' => [
                            'portfolioWebsite'      => $portfolioWebsite->slug,
                        ]
                    ],


                ],

            ]
        );
    }


    public function getBreadcrumbs($routeParameters): array
    {
        return array_merge(
            IndexBanners::make()->getBreadcrumbs(
                'portfolio.portfolio-websites.show.banners.index',
                $routeParameters
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating banner"),
                    ]
                ]
            ]
        );
    }


}
