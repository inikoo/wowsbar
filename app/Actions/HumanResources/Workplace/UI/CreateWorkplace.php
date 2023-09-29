<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Workplace\UI;

use App\Actions\Assets\Country\UI\GetAddressData;
use App\Actions\InertiaAction;
use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Http\Resources\Helpers\AddressFormFieldsResource;
use App\Models\Helpers\Address;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreateWorkplace extends InertiaAction
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
                'title'       => __('new workplace'),
                'pageHead'    => [
                    'title'        => __('new workplace'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.hr.workplaces.index',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('work place'),
                            'fields' => [
                                'name' => [
                                    'type'          => 'input',
                                    'label'         => __('name'),
                                    'placeholder'   => __(''),
                                    'required'      => true
                                ],
                                'type' => [
                                    'type'        => 'select',
                                    'label'       => __('type'),
                                    'options'     => Options::forEnum(WorkplaceTypeEnum::class),
                                    'placeholder' => __('Select a type'),
                                    'mode'        => 'single',
                                    'required'    => true,
                                    'searchable'  => true,
                                ],
                                'address'      => [
                                    'type'    => 'address',
                                    'label'   => __('Address'),
                                    'value'   => AddressFormFieldsResource::make(
                                        new Address(
                                            [
                                                'country_id' => organisation()->country_id,

                                            ]
                                        )
                                    )->getArray(),
                                    'options' => [
                                        'countriesAddressData' => GetAddressData::run()

                                    ],
                                    'required'    => true,
                                ]
                            ]
                        ]

                    ],
                    'route'      => [
                            'name'       => 'org.models.workplace.store',

                    ]

                ],



            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()>hasPermissionTo('hr.edit');
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
            IndexWorkplaces::make()->getBreadcrumbs(),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __('creating workplace'),
                    ]
                ]
            ]
        );
    }

}
