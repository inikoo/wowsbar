<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 13:32:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Customer\SysAdmin;

use App\Actions\UI\Customer\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class SysAdminDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("sysadmin.view");
    }


    public function asController(): bool
    {
        return true;
    }


    public function htmlResponse(): Response
    {
        $customer=customer();

        return Inertia::render(
            'SysAdmin/SysAdminDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('system administration'),
                'pageHead'    => [
                    'title' => __('system administration'),
                ],
                'stats' => [
                    [
                        'name' => __('users'),
                        'stat' => $customer->stats->number_users_status_active,
                        'href' => ['customer.sysadmin.users.index']
                    ],

                ]

            ]
        );
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
                                'name' => 'customer.sysadmin.dashboard'
                            ],
                            'label'  => __('system administration'),
                        ]
                    ]
                ]
            );
    }
}
