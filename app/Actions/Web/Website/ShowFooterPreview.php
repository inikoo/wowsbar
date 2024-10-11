<?php
/*
 * Author: Artha <artha@aw-advantage.com>
 * Created: Thu, 25 Apr 2024 16:56:22 Central Indonesia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Models\Web\Website;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Arr;

class ShowFooterPreview
{
    use AsAction;

    public bool $asAction = false;

    public function handle(Website $website): Website
    {
        return $website;
    }

    public function htmlResponse(Website $website, ActionRequest $request): Response
    {
        return Inertia::render(
            'Banners/PreviewFooterWorkshop',
            [
                'footer'        => [
                    'data' => Arr::get($website->compiled_layout, 'footer')
                ],
                'autosaveRoute' => [
                    'name'       => '',
                    'parameters' => [
                        'website' => null
                    ],
                ]
            ]
        );
    }

    public function asController(ActionRequest $request): Website
    {
        $website = $request->get('website');

        return $website;
    }
}
