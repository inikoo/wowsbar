<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Thu, 25 Apr 2024 16:56:22 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Models\Portfolio\PortfolioWebsite;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Arr;

class ShowFooterPreview
{
    use AsAction;

    public bool $asAction = false;

    public function handle(PortfolioWebsite $portfolioWebsite): PortfolioWebsite
    {
        return $portfolioWebsite;
    }

    public function htmlResponse(PortfolioWebsite $portfolioWebsite, ActionRequest $request): Response
    {
        return Inertia::render(
            'Footer/PreviewFooterWorkshop',
            [
                'footer'        => [
                    'data' => Arr::get($portfolioWebsite->compiled_layout, 'footer')
                ],
                'autosaveRoute' => [
                    'name'       => 'customer.models.portfolio-website.footers.autosave',
                    'parameters' => [
                        'portfolioWebsite' => $portfolioWebsite->id
                    ]
                ],
            ]
        );
    }

    public function asController(PortfolioWebsite $portfolioWebsite, ActionRequest $request): PortfolioWebsite
    {
        return $portfolioWebsite;
    }
}
