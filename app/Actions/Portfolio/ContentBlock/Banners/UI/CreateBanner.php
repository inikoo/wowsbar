<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Thu, 18 May 2023 14:27:30 Central European Summer, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Actions\Portfolio\ContentBlock\Banners\UI;

use App\Actions\InertiaAction;
use App\Models\Portfolio\Website;
use App\Models\Web\WebBlockType;
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


    public function inWebsite(Website $website, ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle($website, $request);
    }


    public function handle(Website $website, ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->parameters()
                ),
                'title'       => __('new banner'),
                'pageHead'    => [
                    'title'        => __('banner'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'tertiary',
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($this->originalParameters)
                            ]
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
                        'name'       => 'models.website.web-block-type.banner.store',
                        'arguments'  => [
                            'website'     => $website->slug,
                            'webBlockType'=> WebBlockType::where('slug', 'banner')->first()
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
                'portfolio.websites.show.banners.index',
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
