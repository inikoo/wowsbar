<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\UI;

use App\Actions\Assets\Country\UI\GetCountriesOptions;
use App\Actions\Assets\Currency\UI\GetCurrenciesOptions;
use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Actions\Assets\TimeZone\UI\GetTimeZonesOptions;
use App\Actions\InertiaAction;
use App\Enums\Market\Shop\ShopTypeEnum;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreateShop extends InertiaAction
{
    /**
     * @throws Exception
     */
    public function handle(): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new shop'),
                'pageHead'    => [
                    'title'        => __('new shop'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.shops.index',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData'    => [
                    'blueprint' => [
                        [
                            'title'  => __('detail'),
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
                                'type' => [
                                    'type'         => 'select',
                                    'label'        => __('type'),
                                    'placeholder'  => 'Select a Type',
                                    'options'      => Options::forEnum(ShopTypeEnum::class),
                                    'required'     => true,
                                    'mode'         => 'single',
                                    'searchable'   => true
                                ],
                            ]
                        ],

                        [
                            'title'  => __('localization'),
                            'icon'   => 'fa-light fa-phone',
                            'fields' => [
                                'country_id'  => [
                                    'type'        => 'select',
                                    'label'       => __('country'),
                                    'placeholder' => __('Select a country'),
                                    'options'     => GetCountriesOptions::run(),
                                    'value'       => organisation()->country_id,
                                    'required'    => true,
                                    'mode'        => 'single'
                                ],
                                'language_id' => [
                                    'type'        => 'select',
                                    'label'       => __('language'),
                                    'placeholder' => __('Select a language'),
                                    'options'     => GetLanguagesOptions::make()->all(),
                                    'value'       => organisation()->language_id,
                                    'required'    => true,
                                    'mode'        => 'single'
                                ],
                                'currency_id' => [
                                    'type'        => 'select',
                                    'label'       => __('currency'),
                                    'placeholder' => __('Select a currency'),
                                    'options'     => GetCurrenciesOptions::run(),
                                    'value'       => organisation()->currency_id,
                                    'required'    => true,
                                    'mode'        => 'single'
                                ],
                                'timezone_id' => [
                                    'type'        => 'select',
                                    'label'       => __('timezone'),
                                    'placeholder' => __('Select a timezone'),
                                    'options'     => GetTimeZonesOptions::run(),
                                    'value'       => organisation()->timezone_id,
                                    'required'    => true,
                                    'mode'        => 'single'
                                ],

                            ]
                        ],
                        [
                            'title'  => __('contact/details'),
                            'fields' => [
                                'contact_name' => [
                                    'type'  => 'input',
                                    'label' => __('contact name'),
                                    'value' => '',
                                ],
                                'company_name' => [
                                    'type'  => 'input',
                                    'label' => __('company name'),
                                    'value' => '',
                                ],
                                'email'        => [
                                    'type'    => 'input',
                                    'label'   => __('email'),
                                    'value'   => '',
                                    'options' => [
                                        'inputType' => 'email'
                                    ]
                                ],
                                'phone'        => [
                                    'type'  => 'phone',
                                    'label' => __('telephone'),
                                    'value' => ''
                                ],
                            ]
                        ],
                    ],
                    'route'     => [
                        'name' => 'org.models.shop.store',
                    ]
                ],

            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can('shops.edit');
    }


    /**
     * @throws Exception
     */
    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle();
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexShops::make()->getBreadcrumbs(),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating shop"),
                    ]
                ]
            ]
        );
    }
}
