<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:35 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Payment\UI;

use App\Actions\InertiaAction;
use App\Enums\UI\PaymentTabsEnum;
use App\Models\Accounting\Payment;
use App\Models\Accounting\PaymentServiceProvider;
use App\Models\OMS\Order;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class EditPayment extends InertiaAction
{
    use HasUIPayment;
    public function handle(Payment $payment): Payment
    {
        return $payment;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->can('accounting.edit');
        return $request->user()->hasPermissionTo("accounting.view");
    }

    public function inOrganisation(Payment $payment, ActionRequest $request): Payment
    {
        $this->initialisation($request);
        return $this->handle($payment);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inPaymentServiceProvider(PaymentServiceProvider $paymentServiceProvider, Payment $payment, ActionRequest $request): Payment
    {
        $this->initialisation($request)->withTab(PaymentTabsEnum::values());
        return $this->handle($payment);
    }




    /** @noinspection PhpUnusedParameterInspection */
    public function inOrder(Order $order, Payment $payment, ActionRequest $request): Payment
    {
        $this->initialisation($request)->withTab(PaymentTabsEnum::values());
        return $this->handle($payment);
    }


    public function htmlResponse(Payment $payment, ActionRequest $request): Response
    {
        return Inertia::render(
            'EditModel',
            [
                'title'       => __('payment'),
                'breadcrumbs' => ShowPayment::make()->getBreadcrumbs($request->route()->getName(), $request->route()->parameters),
                'pageHead'    => [
                    'title'     => $payment->reference,
                    'exitEdit'  => [
                        'route' => [
                            'name'       => preg_replace('/edit$/', 'show', $request->route()->getName()),
                            'parameters' => array_values($this->originalParameters)
                        ]
                    ],


                ],

                'formData' => [
                    'blueprint' => [
                        [
                            'title'  => __('id'),
                            'fields' => [
                                'amount' => [
                                    'type'  => 'input',
                                    'label' => __('amount'),
                                    'value' => $payment->amount
                                ],
                                'date' => [
                                    'type'  => 'input',
                                    'label' => __('label'),
                                    'value' => $payment->date
                                ],
                            ]
                        ]

                    ],
                    'args' => [
                        'updateRoute' => [
                            'name'      => 'models.payment.update',
                            'parameters'=> $payment->slug

                        ],
                    ]
                ]
            ]
        );
    }
}
