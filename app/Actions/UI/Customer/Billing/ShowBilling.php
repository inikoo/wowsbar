<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 31 Aug 2023 10:07:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Billing;

use App\Actions\InertiaAction;
use App\Enums\UI\Customer\PortfolioDashboardTabsEnum;
use App\Models\Accounting\Payment;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBilling extends InertiaAction
{
    public function handle(Payment $payment): Payment
    {
        return $payment;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("portfolio.view");
    }

    public function asController(Payment $payment, ActionRequest $request): Payment
    {
        $this->initialisation($request)->withTab(PortfolioDashboardTabsEnum::values());

        return $this->handle($payment);
    }

    public function htmlResponse(Payment $payment, ActionRequest $request): Response
    {
        return Inertia::render(
            'Billing/Billing',
            [
                'breadcrumbs' => $this->getBreadcrumbs($payment),
                'title'       => __('billing'),
                'pageHead'    => [
                    'title' => __($payment->reference),
                    'icon'  => [
                        'icon'    => ['fal', 'fa-credit-card'],
                        'tooltip' => __('portfolio')
                    ],
                ],

                'payment_url' => Arr::get($payment->data, 'invoice_url')
            ]
        );
    }

    public function getBreadcrumbs(Payment $payment): array
    {
        return
            array_merge(
                ShowBillingDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'label' => __($payment->reference),
                        ]
                    ]
                ]
            );
    }


}
