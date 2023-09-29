<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider\UI;

use App\Actions\InertiaAction;
use App\Enums\Accounting\PaymentServiceProvider\PaymentServiceProviderTypeEnum;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Spatie\LaravelOptions\Options;

class CreatePaymentServiceProvider extends InertiaAction
{
    /**
     * @throws \Exception
     */
    public function handle(): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new payment service provider'),
                'pageHead'    => [
                    'title'        => __('new payment service provider'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.accounting.payment-service-providers.index',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('create payment service provider'),
                            'fields' => [
                                'code' => [
                                    'type'     => 'input',
                                    'label'    => __('code'),
                                    'value'    => '',
                                    'required' => true
                                ],
                                'type' => [
                                    'type'        => 'select',
                                    'label'       => __('type'),
                                    'options'     => Options::forEnum(PaymentServiceProviderTypeEnum::class),
                                    'searchable'  => true,
                                    'placeholder' => __('select a type'),
                                    'required'    => true,
                                    'mode'        => 'single'
                                ],
                            ]
                        ]
                    ],
                    'route'      => [
                        'name'       => 'models.payment-service-provider.store',
                    ]
                ],

            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()>hasPermissionTo('inventory.warehouses.edit');
    }


    /**
     * @throws \Exception
     */
    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);

        return $this->handle();
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexPaymentServiceProviders::make()->getBreadcrumbs(),
            [
                [
                    'type'         => 'creatingModel',
                    'creatingModel'=> [
                        'label'=> __('creating payment service provider'),
                    ]
                ]
            ]
        );
    }
}
