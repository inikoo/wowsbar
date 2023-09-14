<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 14 Sep 2023 15:10:46 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\Web\Webpage\IndexWebpages;
use App\Models\Organisation\Web\Webpage;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateWebpage extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('website.edit');
    }


    public function asController(ActionRequest $request): Webpage
    {
        $this->initialisation($request);
        return organisation()->website->home;

    }


    public function htmlResponse(Webpage $parent, ActionRequest $request): Response
    {

        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new webpage'),
                'pageHead'    => [
                    'title'        => __('new webpage'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' => [
                                'name'       => preg_replace('/create$/', 'index', $request->route()->getName()),
                                'parameters' => array_values($request->route()->originalParameters())
                            ]

                        ]
                    ]


                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('Id'),
                            'icon'   => ['fal','fa-fingerprint'],
                            'fields' => [

                                'code' => [
                                    'type'      => 'input',
                                    'label'     => __('code'),
                                    'value'     => '',
                                    'required'  => true,
                                ],

                                'url' => [
                                    'type'      => 'inputWithAddOn',
                                    'label'     => __('url'),
                                    'leftAddOn' => [
                                        'label' => 'https://'.$parent->website->domain.'/'
                                    ],
                                    'value'     => '',
                                    'required'  => true,
                                ],
                            ]
                        ]


                    ],
                    'route'     => [
                        'name'     => 'org.models.webpage.store',
                        'arguments'=> [$parent->id]
                    ],


                ],

            ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexWebpages::make()->getBreadcrumbs(),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("webpage"),
                    ]
                ]
            ]
        );
    }


}
