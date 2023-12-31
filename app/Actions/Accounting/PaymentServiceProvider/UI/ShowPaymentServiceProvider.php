<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentServiceProvider\UI;

use App\Actions\Accounting\Payment\UI\IndexPayments;
use App\Actions\Accounting\PaymentAccount\UI\IndexPaymentAccounts;
use App\Actions\Accounting\PaymentServiceProvider\GetPaymentServiceProviderShowcase;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Accounting\AccountingDashboard;
use App\Enums\UI\Organisation\PaymentServiceProviderTabsEnum;
use App\Http\Resources\Accounting\PaymentAccountResource;
use App\Http\Resources\Accounting\PaymentResource;
use App\Http\Resources\Accounting\PaymentServiceProviderResource;
use App\Http\Resources\History\HistoryResource;
use App\Models\Accounting\PaymentServiceProvider;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowPaymentServiceProvider extends InertiaAction
{
    public function handle(PaymentServiceProvider $paymentServiceProvider): PaymentServiceProvider
    {
        return $paymentServiceProvider;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit   = $request->user()->hasPermissionTo('accounting.edit');
        $this->canDelete = $request->user()->hasPermissionTo('accounting.edit');

        return $request->user()->hasPermissionTo("accounting.view");
    }

    public function asController(PaymentServiceProvider $paymentServiceProvider, ActionRequest $request): PaymentServiceProvider
    {
        $this->initialisation($request)->withTab(PaymentServiceProviderTabsEnum::values());
        return $this->handle($paymentServiceProvider);
    }

    public function htmlResponse(PaymentServiceProvider $paymentServiceProvider, ActionRequest $request): Response
    {
        return Inertia::render(
            'Accounting/PaymentServiceProvider',
            [
                'title'       => __('payment service provider'),
                'breadcrumbs' => $this->getBreadcrumbs($paymentServiceProvider),
                'navigation'  => [
                    'previous' => $this->getPrevious($paymentServiceProvider, $request),
                    'next'     => $this->getNext($paymentServiceProvider, $request),
                ],
                'pageHead' => [
                    'icon' =>
                        [
                            'icon'  => ['fal', 'fa-cash-register'],
                            'title' => __('payment service provider')
                        ],
                    'title' => $paymentServiceProvider->slug,
                    /* 'actions' => [
                         $this->canEdit ? [
                             'type'  => 'button',
                             'style' => 'edit',
                             'route' => [
                                 'name'       => preg_replace('/show$/', 'edit', $request->route()->getName()),
                                 'parameters' => array_values($this->originalParameters)
                             ]
                         ] : []],
                         $this->canDelete ? [
                             'type'  => 'button',
                             'style' => 'delete',
                             'route' => [
                                 'name'       => 'org.accounting.payment-service-providers.remove',
                                 'parameters' => array_values($this->originalParameters)
                             ]
                         ] : []]
                     ], */
                    'meta' => [
                        [
                            'name'   => trans_choice('account | accounts', $paymentServiceProvider->stats->number_accounts),
                            'number' => $paymentServiceProvider->stats->number_accounts,
                            'href'   => [
                                'org.accounting.payment-service-providers.show.payment-accounts.index',
                                $paymentServiceProvider->slug
                            ],
                            'leftIcon' => [
                                'icon'    => 'fal fa-money-check-alt',
                                'tooltip' => __('accounts')
                            ]
                        ],
                        [
                            'name'   => trans_choice('payment | payments', $paymentServiceProvider->stats->number_payments),
                            'number' => $paymentServiceProvider->stats->number_payments,
                            'href'   => [
                                'org.accounting.payment-service-providers.show.payments.index',
                                $paymentServiceProvider->slug
                            ],
                            'leftIcon' => [
                                'icon'    => 'fal fa-coins',
                                'tooltip' => __('payments')
                            ]
                        ]
                    ]
                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => PaymentServiceProviderTabsEnum::navigation()
                ],
                PaymentServiceProviderTabsEnum::SHOWCASE->value => $this->tab == PaymentServiceProviderTabsEnum::SHOWCASE->value ?
                    fn () => GetPaymentServiceProviderShowcase::run($paymentServiceProvider)
                    : Inertia::lazy(fn () => GetPaymentServiceProviderShowcase::run($paymentServiceProvider)),

                PaymentServiceProviderTabsEnum::PAYMENTS->value => $this->tab == PaymentServiceProviderTabsEnum::PAYMENTS->value
                    ?
                    fn () => PaymentResource::collection(
                        \App\Actions\Accounting\Payment\UI\IndexPayments::run(
                            parent: $paymentServiceProvider,
                            prefix: 'payments'
                        )
                    )
                    : Inertia::lazy(fn () => PaymentResource::collection(
                        \App\Actions\Accounting\Payment\UI\IndexPayments::run(
                            parent: $paymentServiceProvider,
                            prefix: 'payments'
                        )
                    )),
                PaymentServiceProviderTabsEnum::PAYMENT_ACCOUNTS->value => $this->tab == PaymentServiceProviderTabsEnum::PAYMENT_ACCOUNTS->value
                    ?
                    fn () => PaymentAccountResource::collection(
                        IndexPaymentAccounts::run(
                            parent: $paymentServiceProvider,
                            prefix: 'payment_accounts'
                        )
                    )
                    : Inertia::lazy(fn () => PaymentAccountResource::collection(
                        IndexPaymentAccounts::run(
                            parent: $paymentServiceProvider,
                            prefix: 'payment_accounts'
                        )
                    )),

                PaymentServiceProviderTabsEnum::HISTORY->value => $this->tab == PaymentServiceProviderTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run($paymentServiceProvider))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run($paymentServiceProvider)))
            ]
        )
            ->table(
                IndexPayments::make()->tableStructure(
                    modelOperations: [
                        'createLink' => [
                            $this->canEdit ? [
                                'route' => [
                                    'name'       => 'org.accounting.payment-service-providers.show.payments.create',
                                    'parameters' => array_values($this->originalParameters)
                                ],
                                'label' => __('payment')
                            ] : false
                        ],
                    ],
                    prefix: 'payments'
                )
            )
            ->table(IndexPaymentAccounts::make()->tableStructure(
                //            modelOperations: [
                //                'createLink' => $this->canEdit ? [
                //                    'route' => [
                //                        'name'       => 'org.accounting.payment-service-providers.show.payment-accounts.create',
                //                        'parameters' => array_values($this->originalParameters)
                //                    ],
                //                    'label' => __('payment account')
                //                ] : false,
                //            ],
                //            prefix: 'payments'
            ))
            ->table(IndexHistory::make()->tableStructure());
    }

    public function jsonResponse(PaymentServiceProvider $paymentServiceProvider): PaymentServiceProviderResource
    {
        return new PaymentServiceProviderResource($paymentServiceProvider);
    }

    public function getBreadcrumbs(PaymentServiceProvider $paymentServiceProvider, $suffix = null): array
    {
        return array_merge(
            AccountingDashboard::make()->getBreadcrumbs('org.accounting.dashboard.show', []),
            [
                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => [
                                'name' => 'org.accounting.payment-service-providers.index',
                            ],
                            'label' => __('providers')
                        ],
                        'model' => [
                            'route' => [
                                'name'       => 'org.accounting.payment-service-providers.show',
                                'parameters' => [$paymentServiceProvider->slug]
                            ],
                            'label' => $paymentServiceProvider->slug,
                        ],
                    ],
                    'suffix' => $suffix,
                ],
            ]
        );
    }

    public function getPrevious(PaymentServiceProvider $paymentServiceProvider, ActionRequest $request): ?array
    {
        $previous = PaymentServiceProvider::where('code', '<', $paymentServiceProvider->code)->orderBy('code', 'desc')->first();
        return $this->getNavigation($previous, $request->route()->getName());

    }

    public function getNext(PaymentServiceProvider $paymentServiceProvider, ActionRequest $request): ?array
    {
        $next = PaymentServiceProvider::where('code', '>', $paymentServiceProvider->code)->orderBy('code')->first();
        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PaymentServiceProvider $paymentServiceProvider, string $routeName): ?array
    {
        if (!$paymentServiceProvider) {
            return null;
        }
        return match ($routeName) {
            'org.accounting.payment-service-providers.show' => [
                'label' => $paymentServiceProvider->code,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'paymentServiceProvider' => $paymentServiceProvider->slug
                    ]

                ]
            ]
        };
    }
}
