<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 06 Mar 2023 18:40:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Dashboard;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowPublicDashboard
{
    use AsAction;

    public function handle(): Response
    {
        return Inertia::render(
            'Public/Dashboard',
            [
                'title'       => __('dashboard'),
                'breadcrumbs' => $this->getBreadcrumbs(__('dashboard')),
            ]
        );
    }

    public function getBreadcrumbs($label = null): array
    {
        return [
            [

                'type'   => 'simple',
                'simple' => [
                    'icon'  => 'fal fa-tachometer-alt-fast',
                    'label' => $label,
                    'route' => [
                        'name' => 'customer.dashboard.show'
                    ]
                ]

            ],

        ];
    }
}
