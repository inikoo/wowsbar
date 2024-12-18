<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:33:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Accounting\PaymentAccount\UI;

use App\Actions\Accounting\Payment\UI\IndexPayments;
use App\Actions\Helpers\History\IndexHistory;
use App\Actions\InertiaAction;
use App\Actions\UI\Organisation\Accounting\AccountingDashboard;
use App\Enums\UI\Organisation\PaymentAccountTabsEnum;
use App\Http\Resources\Accounting\PaymentAccountResource;
use App\Http\Resources\Accounting\PaymentResource;
use App\Http\Resources\History\HistoryResource;
use App\Models\Accounting\PaymentAccount;
use App\Models\Accounting\PaymentServiceProvider;
use App\Models\Market\Shop;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

/**
 * @property \App\Models\Accounting\PaymentAccount $paymentAccount
 */
class ShowPaymentAccount extends InertiaAction
{
    public function handle(PaymentAccount $paymentAccount): PaymentAccount
    {
        return $paymentAccount;
    }

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->user()->hasPermissionTo('accounting.edit');

        return $request->user()->hasPermissionTo("accounting.view");
    }

    public function asController(PaymentAccount $paymentAccount, ActionRequest $request): PaymentAccount
    {
        $this->initialisation($request)->withTab(PaymentAccountTabsEnum::values());
        return $this->handle($paymentAccount);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inPaymentServiceProvider(PaymentServiceProvider $paymentServiceProvider, PaymentAccount $paymentAccount, ActionRequest $request): PaymentAccount
    {
        $this->initialisation($request)->withTab(PaymentAccountTabsEnum::values());
        return $this->handle($paymentAccount);
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function inShop(Shop $shop, PaymentAccount $paymentAccount, ActionRequest $request): PaymentAccount
    {
        $this->initialisation($request)->withTab(PaymentAccountTabsEnum::values());
        return $this->handle($paymentAccount);
    }

    public function htmlResponse(PaymentAccount $paymentAccount, ActionRequest $request): Response
    {
        return Inertia::render(
            'Accounting/PaymentAccount',
            [
                'title'       => $paymentAccount->name,
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'navigation' => [
                    'previous' => $this->getPrevious($paymentAccount, $request),
                    'next'     => $this->getNext($paymentAccount, $request),
                ],
                'pageHead' => [
                    'icon' =>
                        [
                            'icon'  => ['fal', 'fa-money-check-alt'],
                            'title' => __('payment account')
                        ],
                    'title'  => $paymentAccount->slug,
                    'create' => $this->canEdit
                    && (
                        $request->route()->getName() == 'org.accounting.payment-service-providers.show.payment-accounts.show' or
                        $request->route()->getName() == 'org.accounting.payment-accounts.show'
                    ) ? [
                        'route' => [
                            'name'       => preg_replace('/show$/', 'show.payments.create', $request->route()->getName()),
                            'parameters' => array_values($this->originalParameters)
                        ],
                        'label' => __('payment')
                    ] : false,
                    'meta' => [
                        [
                            'name'   => trans_choice('payment | payments', $paymentAccount->stats->number_payments),
                            'number' => $paymentAccount->stats->number_payments,
                            'href'   => match ($request->route()->getName()) {
                                'org.accounting.payment-service-providers.show.payment-accounts.show' => [
                                    'org.accounting.payment-service-providers.show.payment-accounts.show.payments.index',
                                    [$paymentAccount->paymentServiceProvider->slug, $paymentAccount->slug]
                                ],
                                default => [
                                    'org.accounting.payment-accounts.show.payments.index',
                                    $paymentAccount->slug
                                ]
                            },
                            'leftIcon' => [
                                'icon'    => 'fal fa-coins',
                                'tooltip' => __('payments')
                            ]
                        ],

                    ],

                ],
                'tabs' => [
                    'current'    => $this->tab,
                    'navigation' => PaymentAccountTabsEnum::navigation()

                ],

                PaymentAccountTabsEnum::PAYMENTS->value => $this->tab == PaymentAccountTabsEnum::PAYMENTS->value ?
                    fn () => PaymentResource::collection(
                        IndexPayments::run(
                            parent: $this->paymentAccount,
                            prefix: 'payments'
                        )
                    )
                    : Inertia::lazy(fn () => PaymentResource::collection(
                        IndexPayments::run(
                            parent: $this->paymentAccount,
                            prefix: 'payments'
                        )
                    )),
                PaymentAccountTabsEnum::HISTORY->value => $this->tab == PaymentAccountTabsEnum::HISTORY->value ?
                    fn () => HistoryResource::collection(IndexHistory::run($paymentAccount))
                    : Inertia::lazy(fn () => HistoryResource::collection(IndexHistory::run($paymentAccount)))

            ]
        )->table(
            \App\Actions\Accounting\Payment\UI\IndexPayments::make()->tableStructure(
                modelOperations: [
                'createLink' => [
                    $this->canEdit ? [
                        'route' => [
                            'name'       => 'org.accounting.payment-accounts.show.payments.create',
                            'parameters' => array_values([$paymentAccount->slug])
                        ],
                        'label' => __('products')
                    ] : false
                ]
            ],
                prefix: 'payments'
            )
        )->table(IndexHistory::make()->tableStructure());
    }


    public function jsonResponse(PaymentAccount $paymentAccount): PaymentAccountResource
    {
        return new PaymentAccountResource($paymentAccount);
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters, string $suffix = ''): array
    {
        $headCrumb = function (PaymentAccount $paymentAccount, array $routeParameters, string $suffix) {
            return [
                [
                    'type'           => 'modelWithIndex',
                    'modelWithIndex' => [
                        'index' => [
                            'route' => $routeParameters['index'],
                            'label' => __('payment accounts')
                        ],
                        'model' => [
                            'route' => $routeParameters['model'],
                            'label' => $paymentAccount->code,
                        ],

                    ],
                    'suffix' => $suffix
                ],
            ];
        };

        return match ($routeName) {
            'org.accounting.shops.show.payment-accounts.show' =>
            array_merge(
                (new AccountingDashboard())->getBreadcrumbs('org.accounting.shops.show.dashboard', $routeParameters),
                $headCrumb(
                    $routeParameters['paymentAccount'],
                    [
                        'index' => [
                            'name'       => 'org.accounting.shops.show.payment-accounts.index',
                            'parameters' => [
                                $routeParameters['shop']->slug,
                            ]
                        ],
                        'model' => [
                            'name'       => 'org.accounting.shops.show.payment-accounts.show',
                            'parameters' => [
                                $routeParameters['shop']->slug,
                                $routeParameters['paymentAccount']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            'org.accounting.payment-accounts.show' =>
            array_merge(
                (new AccountingDashboard())->getBreadcrumbs('org.accounting.dashboard.show', $routeParameters),
                $headCrumb(
                    $routeParameters['paymentAccount'],
                    [
                        'index' => [
                            'name'       => 'org.accounting.payment-accounts.index',
                            'parameters' => []
                        ],
                        'model' => [
                            'name'       => 'org.accounting.payment-accounts.show',
                            'parameters' => [$routeParameters['paymentAccount']->slug]
                        ]
                    ],
                    $suffix
                )
            ),
            'org.accounting.payment-service-providers.show.payment-accounts.show' =>
            array_merge(
                \App\Actions\Accounting\PaymentServiceProvider\UI\ShowPaymentServiceProvider::make()->getBreadcrumbs($routeParameters['paymentServiceProvider']),
                $headCrumb(
                    $routeParameters['paymentAccount'],
                    [
                        'index' => [
                            'name'       => 'org.accounting.payment-service-providers.show.payment-accounts.index',
                            'parameters' => [
                                $routeParameters['paymentServiceProvider']->slug,
                            ]
                        ],
                        'model' => [
                            'name'       => 'org.accounting.payment-service-providers.show.payment-accounts.show',
                            'parameters' => [
                                $routeParameters['paymentServiceProvider']->slug,
                                $routeParameters['paymentAccount']->slug
                            ]
                        ]
                    ],
                    $suffix
                )
            ),
            default => []
        };
    }

    public function getPrevious(PaymentAccount $paymentAccount, ActionRequest $request): ?array
    {
        $previous = PaymentAccount::where('code', '<', $paymentAccount->code)->orderBy('code', 'desc')->first();

        return $this->getNavigation($previous, $request->route()->getName());
    }

    public function getNext(PaymentAccount $paymentAccount, ActionRequest $request): ?array
    {
        $next = PaymentAccount::where('code', '>', $paymentAccount->code)->orderBy('code')->first();

        return $this->getNavigation($next, $request->route()->getName());
    }

    private function getNavigation(?PaymentAccount $paymentAccount, string $routeName): ?array
    {
        if (!$paymentAccount) {
            return null;
        }

        return match ($routeName) {
            'org.accounting.payment-accounts.show' => [
                'label' => $paymentAccount->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'paymentAccount' => $paymentAccount->slug
                    ]

                ]
            ],
            'org.accounting.payment-service-providers.show.payment-accounts.show' => [
                'label' => $paymentAccount->name,
                'route' => [
                    'name'       => $routeName,
                    'parameters' => [
                        'paymentServiceProvider' => $paymentAccount->paymentServiceProvider->slug,
                        'paymentAccount'         => $paymentAccount->slug
                    ]

                ]
            ],
            default => null
        };
    }
}
