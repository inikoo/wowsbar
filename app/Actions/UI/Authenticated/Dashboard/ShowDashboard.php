<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:01:45 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Authenticated\Dashboard;

use App\Actions\Portfolio\Banner\UI\GetLastEditedBanner;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowDashboard
{
    use AsAction;

    public function asController(ActionRequest $request): Response
    {
        return Inertia::render(
            'Dashboard',
            [
                'title'       => __('dashboard'),
                'breadcrumbs' => $this->getBreadcrumbs(__('dashboard')),
                'banners'     => GetLastEditedBanner::run(customer()),
                'userName'    => $request->user()->contact_name??$request->user()->username
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
                        'name' => 'tenant.dashboard.show'
                    ]
                ]

            ],

        ];
    }
}
