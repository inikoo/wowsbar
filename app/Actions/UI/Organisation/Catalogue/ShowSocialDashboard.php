<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 05 Oct 2023 19:06:23 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Organisation\Catalogue;

use App\Actions\Portfolio\PortfolioSocialAccount\UI\IndexPortfolioSocialAccounts;
use App\Actions\UI\Organisation\Dashboard\ShowDashboard;
use App\Actions\UI\WithInertia;
use App\Http\Resources\Portfolio\PortfolioSocialAccountResource;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowSocialDashboard
{
    use AsAction;
    use WithInertia;

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("catalogue.social.view");
    }


    public function asController(): void
    {
        $this->validateAttributes();
    }


    public function htmlResponse(): Response
    {
        $org = organisation();

        return Inertia::render(
            'Catalogue/Social/Socials',
            [
                'breadcrumbs' => $this->getBreadcrumbs(),
                'title'       => __('social dashboard'),
                'pageHead'    => [
                    'title' => __('social dashboard'),
                ],

                'data' => PortfolioSocialAccountResource::collection(IndexPortfolioSocialAccounts::run())
            ]
        )->table(IndexPortfolioSocialAccounts::make()->tableStructure(prefix: 'social_account'));
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
                                'name' => 'org.social.dashboard'
                            ],
                            'label' => __('social'),
                        ]
                    ]
                ]
            );
    }
}
