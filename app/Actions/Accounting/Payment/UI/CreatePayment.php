<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Payment\UI;

use App\Actions\InertiaAction;
use App\Models\Accounting\PaymentAccount;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class CreatePayment extends InertiaAction
{
    public function htmlResponse(ActionRequest $request): Response
    {
        return Inertia::render(
            'CreateModel',
            [
                'breadcrumbs' => $this->getBreadcrumbs($request->route()->getName(), array_values($this->originalParameters)),
                'title'       => __('new payment'),
                'pageHead'    => [
                    'title'        => __('new payment'),
                    'cancelCreate' => [
                        'route' => [
                            'name' => match ($request->route()->getName()) {
                                'customer.billing.create'                              => 'customer.billing.show',
                                default                                                => preg_replace('/create$/', 'index', $request->route()->getName())
                            },
                            'parameters' => array_values($this->originalParameters)
                        ]
                    ]
                ],
                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('payment'),
                            'fields' => [
                                'reference' => [
                                    'type'     => 'input',
                                    'label'    => __('reference'),
                                    'value'    => Str::upper(Str::random()),
                                    'readonly' => true
                                ]
                            ]
                        ],
                        [
                            'title'  => __('Details'),
                            'fields' => [
                                'amount' => [
                                    'type'  => 'input',
                                    'label' => __('amount'),
                                    'value' => ''
                                ],
                                'org_amount' => [
                                    'type'  => 'input',
                                    'label' => __('customer currency amount'),
                                    'value' => ''
                                ],
                                'date' => [
                                    'type'  => 'date',
                                    'label' => __('date'),
                                    'value' => ''
                                ]
                            ]
                        ]
                    ],
                    'route' => [
                        'name' => 'customer.models.billing.store'
                    ]
                ],
            ]
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo('billing.edit');
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request);

        return $request;
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inPaymentAccount(PaymentAccount $paymentAccount, ActionRequest $request): void
    {
        $this->initialisation($request);
    }

    public function getBreadcrumbs(string $routeName, array $params): array
    {
        return array_merge(
            IndexPayments::make()->getBreadcrumbs($routeName, $params),
            [
                [
                    'type'          => 'creatingModel',
                    'creatingModel' => [
                        'label' => __("creating payment"),
                    ]
                ]
            ]
        );
    }
}
