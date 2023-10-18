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

class ShowSysAdminDashboard
{
    use AsAction;
    use WithInertia;

    private bool $canEdit=false;

    public function authorize(ActionRequest $request): bool
    {
        $this->canEdit = $request->get('customerUser')->hasPermissionTo('sysadmin.edit');
        return $request->get('customerUser')->hasPermissionTo("sysadmin.view");
    }


    public function asController(ActionRequest $request): ActionRequest
    {
        $request->validate();
        return $request;
    }


    public function htmlResponse(ActionRequest $request): Response
    {
        $customer=$request->get('customer');
        return Inertia::render(
            'SysAdmin/SysAdminDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('account management'),
                'pageHead'    => [
                    'title'     => __('account management'),
                    'actions'   => [
                        $this->canEdit ? [
                            'type'  => 'button',
                            'style' => 'create',
                            'label' => __('create user'),
                            'route' => [
                                'name'       => 'customer.sysadmin.users.create'
                            ]
                        ] : []
                    ]
                ],
                'stats' => [
                    [
                        'name' => __('users'),
                        'stat' => $customer->stats->number_users_status_active,
                        'href' => ['name'=>'customer.sysadmin.users.index']
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
                            'label'  => __('account management'),
                        ]
                    ]
                ]
            );
    }
}
