<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:09:06 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Catalogue;

use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowGoogleAdsDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("catalogue.google-ads.view");
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
                'title'       => __('google ads dashboard'),
                'pageHead'    => [
                    'title' => __('Google ads dashboard'),
                ],
                'stats'       => [
                    [
                        'name' => __('websites'),
                        'stat' => $org->crmStats->number_customer_websites_google_ads,
                        'href' => [
                            'name' => 'org.google-ads.websites.index'
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
                                'name' => 'org.google-ads.dashboard'
                            ],
                            'label' => __('ads'),
                        ]
                    ]
                ]
            );
    }
}
