<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Thu, 18 May 2023 14:27:30 Central European Summer, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Actions\Portfolio\PortfolioWebsite\UI;

use App\Actions\InertiaAction;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreatePortfolioWebsite extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('portfolio.edit');
    }


    public function asController(ActionRequest $request): Response|RedirectResponse
    {
        $this->initialisation($request);

        return $this->handle();
    }


    public function handle(): Response
    {
        return Inertia::render(
            'Tenant/CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new website'),
                'pageHead'    => [
                    'title'        => __('website'),
                    'cancelCreate' => [
                        'route' => [
                            'name'       => 'portfolio.websites.index',
                            'parameters' => array_values($this->originalParameters)
                        ],
                    ]

                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('domain'),
                            'fields' => [

                                'domain' => [
                                    'type'      => 'inputWithAddOn',
                                    'label'     => __('domain'),
                                    'leftAddOn' => [
                                        'label' => 'http://'
                                    ],
                                    'required'  => true,
                                ],


                            ]
                        ],
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
                        'name' => 'models.website.store',
                    ],


                ],

            ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexPortfolioWebsites::make()->getBreadcrumbs(
                'portfolio.websites.index',
                []
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating website"),
                    ]
                ]
            ]
        );
    }


}
