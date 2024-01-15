<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 20 Sep 2023 09:44:19 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\Billing;

use App\Actions\Accounting\Payment\UI\IndexPayments;
use App\Actions\InertiaAction;
use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Models\CRM\Customer;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class ShowBillingDashboard extends InertiaAction
{
    public function authorize(ActionRequest $request): bool
    {
        return $request->get('customerUser')->hasPermissionTo("billing.view");
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $this->initialisation($request);

        return $request;
    }


    public function htmlResponse(ActionRequest $request): Response
    {
        $customer = $request->get('customer');

        return Inertia::render(
            'Billing/BillingDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('Billing'),
                'pageHead'    => [
                    'title'   => __('Billing'),
                    'icon'    => [
                        'icon'    => ['fal', 'fa-credit-card'],
                        'tooltip' => __('Billing')
                    ],
                    'actions' => [
                        [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('Create Billing'),
                            'route' => [
                                'name'       => 'customer.billing.create',
                                'parameters' => []
                            ],
                        ]
                    ]
                ],

                'data' => IndexPayments::run()
            ]
        )->table(IndexPayments::make()->tableStructure(prefix: 'dashboard'));
    }

    private function getDashboard(Customer $customer): array
    {
        return [
            'flatTreeMaps' => [
                [
                    [
                        'name'  => __('websites'),
                        'icon'  => ['fal', 'fa-globe'],
                        'href'  => ['name' => 'customer.portfolio.websites.index'],
                        'index' => [
                            'number' => 0 // todo make stats for portfolio division stuff
                        ]
                    ],
                ]
            ],
        ];
    }

    public function getBreadcrumbs(): array
    {
        return
            array_merge(
                ShowDashboard::make()->getBreadcrumbs(),
                [
                    [
                        'type'   => 'simple',
                        'simple' => [
                            'route' => [
                                'name' => 'customer.billing.dashboard'
                            ],
                            'label' => __('Billing'),
                        ]
                    ]
                ]
            );
    }


}
