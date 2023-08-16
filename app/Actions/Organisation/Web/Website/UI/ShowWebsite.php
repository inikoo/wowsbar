<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 16:47:20 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website\UI;

use App\Actions\UI\Tenant\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowWebsite
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
            'Organisation/Web/Website',
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
                                'name' => 'org.website.show'
                            ],
                            'label'  => __('website'),
                        ]
                    ]
                ]
            );
    }
}
