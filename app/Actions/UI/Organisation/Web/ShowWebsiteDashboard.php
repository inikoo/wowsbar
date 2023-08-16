<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Web;

use App\Actions\UI\Tenant\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowWebsiteDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->can("website.view");
    }


    public function asController(): bool
    {
        return true;
    }


    public function htmlResponse(): Response
    {

        return Inertia::render(
            'Organisation/Website/WebsiteDashboard',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('website'),
                'pageHead'    => [
                    'title' => __('website'),
                ],


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
                                'name' => 'org.website.dashboard'
                            ],
                            'label'  => __('website'),
                        ]
                    ]
                ]
            );
    }
}
