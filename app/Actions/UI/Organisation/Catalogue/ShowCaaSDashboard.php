<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 06 Oct 2023 08:11:55 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Catalogue;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCaaSDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("catalogue.caas.view");
    }


    public function asController(): void
    {
        $this->validateAttributes();
    }


    public function htmlResponse(): Response
    {
        $org = organisation();

        return Inertia::render(
            'Catalogue/CatalogueDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('CaaS dashboard'),
                'pageHead'    => [
                    'title' => __('CaaS dashboard'),
                ],
                'stats'       => [
                    [
                        'name' => __('websites'),
                        'stat' => $org->crmStats->number_customer_websites_banners,
                        'href' => [
                            'name' => 'org.catalogue.departments.index'
                        ]
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
                                'name' => 'org.caas.dashboard'
                            ],
                            'label' => __('CaaS'),
                        ]
                    ]
                ]
            );
    }
}
