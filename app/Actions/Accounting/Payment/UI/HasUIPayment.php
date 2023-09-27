<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\Payment\UI;

use App\Actions\OMS\Order\UI\ShowOrder;
use App\Actions\UI\Accounting\AccountingDashboard;

trait HasUIPayment
{
    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        $payment   =$routeParameters['payment'];
        $headCrumb = function (array $parameters = []) use ($routeName, $payment) {
            return [
                $routeName => [
                    'route'           => $routeName,
                    'routeParameters' => $parameters,
                    'name'            => $payment->slug,
                    'index'           =>
                        match ($routeName) {
                            'org.shops.show.orders.show.payments.show', 'orders.show,payments.show' => null,
                            default => [
                                'route'           => preg_replace('/show$/', 'index', $routeName),
                                'routeParameters' => function () use ($parameters) {
                                    $indexParameters = $parameters;
                                    array_pop($indexParameters);

                                    return $indexParameters;
                                },
                                'overlay'         => __('payments list')
                            ]
                        },


                    'modelLabel' => [
                        'label' => __('payment')
                    ]
                ],
            ];
        };

        return match ($routeName) {
            'org.shops.show.orders.show.payments.show' => array_merge(
                (new ShowOrder())->getBreadcrumbs(
                    'org.shops.show.orders.show',
                    [
                        'shop'  => $routeParameters['shop'],
                        'order' => $routeParameters['order']
                    ]
                ),
                $headCrumb(
                    [
                        $routeParameters['shop']->slug,
                        $routeParameters['order']->slug,
                        $routeParameters['payment']->slug
                    ]
                )
            ),
            'orders.show,payments.show' => array_merge(
                (new \App\Actions\OMS\Order\UI\ShowOrder())->getBreadcrumbs(
                    'orders.show',
                    [
                        'order' => $routeParameters['order']
                    ]
                ),
                $headCrumb(
                    [
                        $routeParameters['order']->slug,
                        $routeParameters['payment']->slug
                    ]
                )
            ),
            'org.accounting.payments.show' => array_merge(
                AccountingDashboard::make()->getBreadcrumbs('org.accounting.dashboard.show', []),
                $headCrumb([$routeParameters['payment']->slug])
            ),
            'org.accounting.payment-service-provider.show.payments.show' => array_merge(
                (new \App\Actions\Accounting\PaymentServiceProvider\UI\ShowPaymentServiceProvider())
                    ->getBreadcrumbs($routeParameters['payment']->paymentAccount->paymentServiceProvider),
                $headCrumb([$routeParameters['payment']->paymentAccount->paymentServiceProvider->slug, $routeParameters['payment']->slug])
            ),
            'org.accounting.payment-accounts.show.payments.show' => array_merge(
                (new \App\Actions\Accounting\PaymentAccount\UI\ShowPaymentAccount())
                    ->getBreadcrumbs('org.accounting.payment-accounts.show', $routeParameters['payment']->paymentAccount),
                $headCrumb([$routeParameters['payment']->paymentAccount->slug, $routeParameters['payment']->slug])
            ),
            'org.accounting.payment-service-provider.show.payment-accounts.show.payments.show' => array_merge(
                (new \App\Actions\Accounting\PaymentAccount\UI\ShowPaymentAccount())
                    ->getBreadcrumbs('org.accounting.payment-service-provider.show.payment-accounts.show', $routeParameters['payment']->paymentAccount),
                $headCrumb(
                    [
                        $routeParameters['payment']->paymentAccount->paymentServiceProvider->slug,
                        $routeParameters['payment']->paymentAccount->slug,
                        $routeParameters['payment']->slug
                    ]
                )
            ),

            default => []
        };
    }
}
