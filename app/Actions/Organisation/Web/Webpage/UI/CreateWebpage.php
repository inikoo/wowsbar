<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 14 Sep 2023 15:10:46 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Webpage\UI;

use App\Actions\InertiaAction;
use App\Actions\Organisation\Web\Webpage\IndexWebpages;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Organisation\Web\Webpage;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateWebpage extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('websites.edit');
    }


    public function asController(ActionRequest $request): Webpage
    {
        $this->initialisation($request);

        return organisation()->website->home;
    }

    public function inWebpage(Webpage $webpage, ActionRequest $request): Webpage
    {
        $this->initialisation($request);

        return $webpage;
    }

    public function htmlResponse(Webpage $parent, ActionRequest $request): Response
    {
        $types = [
            [
                'title'       => __('Content'),
                'description' => __('General content'),
                'value'       => WebpageTypeEnum::CONTENT->value
            ],
            [
                'title'       => __('Shop'),
                'description' => __('Services showcase'),
                'value'       => WebpageTypeEnum::SHOP->value
            ],
        ];


        if ($parent->type == WebpageTypeEnum::STOREFRONT) {
            $types[] = [
                'title'       => WebpageTypeEnum::SMALL_PRINT->label(),
                'description' => __('Privacy, T&C, cookies etc'),
                'value'       => WebpageTypeEnum::SMALL_PRINT->value
            ];
        }


        $type = WebpageTypeEnum::CONTENT->value;


        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new webpage'),
                'pageHead'    => [
                    'title'   => __('new webpage'),
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'route' =>
                                match ($request->route()->getName()) {
                                    'org.websites.webpages.show.webpages.create' => [
                                        'name'       => 'org.websites.webpages.show' ,
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
                            'title'  => __('Type'),
                            'icon'   => ['fal', 'fa-shapes'],
                            'fields' => [

                                'type' => [
                                    'type'     => 'radio',
                                    'mode'     => 'card',
                                    'label'    => __('type'),
                                    'options'  => $types,
                                    'value'    => $type,
                                    'required' => true,
                                ],


                            ]
                        ],

                        [
                            'title'  => __('Id'),
                            'icon'   => ['fal', 'fa-fingerprint'],
                            'fields' => [

                                'code' => [
                                    'type'     => 'input',
                                    'label'    => __('code'),
                                    'value'    => '',
                                    'required' => true,
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
                        'name'      => 'org.models.webpage.store',
                        'arguments' => [$parent->id]
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
