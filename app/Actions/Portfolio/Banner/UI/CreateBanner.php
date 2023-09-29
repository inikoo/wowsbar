<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Banner\UI;

use App\Actions\InertiaAction;
use App\Actions\Portfolio\PortfolioWebsite\UI\GetPortfolioWebsitesOptions;
use App\Models\CRM\Customer;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateBanner extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo('portfolio.edit');
    }

    public function inCustomer(ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle(customer(), $request);
    }

    public function inPortfolioWebsite(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle($portfolioWebsite, $request);
    }


    public function handle(Customer|PortfolioWebsite $parent, ActionRequest $request): Response
    {

        $fields=[];

        $fields[]= [
            'title'  => __('ID/name'),
            'fields' => [

                'code' => [
                    'type'          => 'input',
                    'label'         => __('code'),
                    'placeholder'   => __('Input unique code'),
                    'required'      => true,
                    'value'         => Str::random(3)
                ],
                'name' => [
                    'type'          => 'input',
                    'label'         => __('name'),
                    'placeholder'   => __('Name for new banner'),
                    'required'      => true,
                    'value'         => '',
                ],
            ]
        ];

        if(class_basename($parent)=='Customer') {
            $fields[]= [
                'title'  => __('Website'),
                'fields' => [
                    'portfolio_website_id'  => [
                        'type'        => 'select',
                        'label'       => __('website'),
                        'placeholder' => __('Select a website'),
                        'options'     => GetPortfolioWebsitesOptions::run(),
                        'value'       => null,
                        'required'    => false,
                        'mode'        => 'single'
                    ],

                ]
            ];
        }

        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
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
                                    'customer.portfolio.websites.show.banners.create' =>
                                    [
                                        'name'       => 'customer.portfolio.websites.show',
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
                    'blueprint' => $fields,
                    'route'     =>

                        match (class_basename($parent)) {
                            'Authenticated' => [
                                'name' => 'models.banner.store',
                            ],
                            default => [
                                'name'       => 'models.portfolio-website.banner.store',
                                'parameters' => [
                                    'portfolioWebsite' => $parent->slug,
                                ]
                            ],
                        }


                ],

            ]
        );
    }


    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return array_merge(
            match ($routeName) {
                'customer.portfolio.banners.create' => IndexBanners::make()->getBreadcrumbs(
                    'customer.portfolio.banners.index',
                    $routeParameters
                ),
                default => IndexBanners::make()->getBreadcrumbs(
                    'customer.portfolio.websites.show.banners.index',
                    $routeParameters
                )
            },
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
