<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentAccount\UI;

use App\Actions\InertiaAction;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreatePaymentAccount extends InertiaAction
{
    public function handle(): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('new payment account'),
                'pageHead'    => [
                    'title'        => __('new payment account'),
                    'actions'      => [
                        [
                            'type'  => 'button',
                            'style' => 'cancel',
                            'label' => __('cancel'),
                            'route' => [
                                'name'       => 'org.accounting.payment-accounts.index',
                                'parameters' => array_values($this->originalParameters)
                            ],
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('payment account'),
                            'fields' => [
                                'code' => [
                                    'type'     => 'input',
                                    'label'    => __('code'),
                                    'required' => true
                                ],
                                'name' => [
                                    'type'     => 'input',
                                    'label'    => __('name'),
                                    'required' => true
                                ],
                            ]
                        ]
                    ],
                    'route'      => [
                        'name'       => 'org.models.payment-account.store'
                    ]
                ],


            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return !$request->user()>hasPermissionTo('accounting.edit');
    }


    public function asController(ActionRequest $request): Response
    {
        $this->initialisation($request);
        return $this->handle();
    }

    public function getBreadcrumbs(): array
    {
        return array_merge(
            IndexPaymentAccounts::make()->getBreadcrumbs(
                request()->route()->getName(),
                request()->route()->parameters
            ),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating payment account"),
                    ]
                ]
            ]
        );
    }
}
