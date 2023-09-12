<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 11 Sep 2023 14:49:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\UI\CRM;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CRMDashboard
{
    use AsAction;
    use WithInertia;




    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("crm.view");
    }


    public function asController(ActionRequest $request)
    {

    }



    public function htmlResponse(): Response
    {

        return Inertia::render(
            'CRM/CRMDashboard',
            [
                'breadcrumbs'  => $this->getBreadcrumbs(),
                'title'        => 'CRM',
                'pageHead'     => [
                    'title'     => __('customer relationship manager'),
                    'icon'    => [
                        'title' => __('customers'),
                        'icon'  => 'fal fa-user'
                    ],
                ],
                'stats' => [
                    [
                        'name' => __('customers'),
                        'stat' => organisation()->stats->number_organisation_users_status_active,
                        'href' => ['org.crm.customers.index']
                    ],
                    [
                        'name' => __('prospects'),
                        'stat' => organisation()->stats->number_organisation_users_status_active,
                        'href' => ['org.crm.prospects.index']
                    ]
                ]
            ]
        );
    }


    public function getBreadcrumbs(): array
    {
        return  array_merge(
            ShowDashboard::make()->getBreadcrumbs(),
            [
                [
                    'type'   => 'simple',
                    'simple' => [
                        'route' => [
                            'name' => 'org.crm.dashboard'
                        ],
                        'label' => __('CRM'),
                    ]
                ]
            ]
        );
    }

}
