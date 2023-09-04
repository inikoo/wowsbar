<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 13:14:06 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\SysAdmin;

use App\Actions\UI\Tenant\Dashboard\ShowDashboard;
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
                        'stat' => organisation()->stats->number_organisation_users_status_active,
                        'href' => ['org.sysadmin.users.index']
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
                                'name' => 'org.sysadmin.dashboard'
                            ],
                            'label'  => __('system administration'),
                        ]
                    ]
                ]
            );
    }
}
