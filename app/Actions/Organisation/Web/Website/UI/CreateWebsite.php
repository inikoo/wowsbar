<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:54:38 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website\UI;

use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreateWebsite extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('websites.edit');
    }


    public function asController(Shop $shop, ActionRequest $request): Shop
    {
        $this->initialisation($request);
        return $shop;

    }


    public function htmlResponse(Shop $shop, ActionRequest $request): Response
    {

        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('set up website'),
                'pageHead'    => [
                    'title'        => __('set up website'),



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
                                    'value'     => config('app.domain'),
                                    'readonly'  => true,
                                    'required'  => true,
                                ],


                            ]
                        ]


                    ],
                    'route'     => [
                        'name'      => 'org.models.shop.website.store',
                        'parameters'=> [$shop->id]
                    ],


                ],

            ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return array_merge(
            ShowDashboard::make()->getBreadcrumbs(),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("set up website"),
                    ]
                ]
            ]
        );
    }


}
