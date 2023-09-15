<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Mar 2023 18:42:58 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Accounting;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Models\Organisation\Organisation;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class AccountingDashboard
{
    use AsAction;
    use WithInertia;


    public function handle(): Organisation
    {
        return organisation();
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("accounting.view");
    }

    public function htmlResponse(Organisation $scope, ActionRequest $request): Response
    {
        return Inertia::render(
            'Accounting/AccountingDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(
                    $request->route()->getName(),
                    $request->route()->parameters
                ),
                'title' => __('accounting'),
                'pageHead' => [
                    'title' => __('accounting'),
                ],
                'flatTreeMaps' => [
                    [
                        [
                            'name' => __('accounts'),
                            'icon' => ['fal', 'fa-money-check-alt'],
                            'href' => ['org.accounting.payment-accounts.index'],
                            'index' => [
                                'number' => 0
                            ],
                            'rightSubLink' => [
                                'tooltip' => __('payment methods'),
                                'icon' => ['fal', 'fa-cash-register'],
                                'labelStyle' => 'bordered',
                                'href' => ['org.accounting.payment-service-providers.index'],

                            ]

                        ],
                        [
                            'name' => __('payments'),
                            'icon' => ['fal', 'fa-coins'],
                            'href' => ['org.accounting.payments.index'],
                            'index' => [
                                'number' => 0
                            ]

                        ],
                        [
                            'name' => __('invoices'),
                            'icon' => ['fal', 'fa-file-invoice-dollar'],
                            'href' => ['org.accounting.invoices.index'],
                            'index' => [
                                'number' => 0
                            ]

                        ],

                    ]
                ]
            ]
        );
    }

    public function getBreadcrumbs(string $routeName, array $routeParameters): array
    {
        return match ($routeName) {
            'org.accounting.shops.show.dashboard' => null,
            default =>
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type' => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'org.accounting.dashboard'
                            ],
                            'label' => __('accounting'),
                        ]
                    ]
                ]
            )
        };
    }
}
