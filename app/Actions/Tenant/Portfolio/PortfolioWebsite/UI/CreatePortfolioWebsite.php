<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\PortfolioWebsite\UI;

use App\Actions\InertiaAction;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreatePortfolioWebsite extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('portfolio.edit');
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request);
        return $request;

    }


    public function htmlResponse(ActionRequest $request): Response
    {
        $request->route()->getName();

        return Inertia::render(
            'Tenant/CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new website'),
                'pageHead'    => [
                    'title'        => __('website'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ],
                        ]
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
                                        'label' => 'https://'
                                    ],
                                    'required'  => true,
                                ],


                            ]
                        ],
                        [
                            'title'  => __('ID/name'),
                            'fields' => [

                                'code' => [
                                    'type'      => 'input',
                                    'label'     => __('code'),
                                    'required'  => true,
                                    'maxLength' => 8
                                ],
                                'name' => [
                                    'type'      => 'input',
                                    'label'     => __('name'),
                                    'required'  => true,
                                    'value'     => '',
                                ],


                            ]
                        ],


                    ],
                    'route'     => [
                        'name' => 'models.portfolio-website.store',
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
