<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\UI;

use App\Models\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetWebsiteWorkshopLayout
{
    use AsObject;

    public function handle(Website $website, ActionRequest $request): array
    {

        $sections['properties'] = [
            'label'  => __('Properties'),
            'icon'   => 'fal fa-key',
            'fields' => [
                "logo" => [
                    "type"  => "avatar",
                    "label" => __("logo"),
                    "value" => !blank($website->logo_id) ? $website->logoImageSources(320, 320) : null,

                ],
            ]
        ];

        $currentSection = 'properties';
        if ($request->has('section') and Arr::has($sections, $request->get('section'))) {
            $currentSection = $request->get('section');
        }


        return [
            "formData" => [
                'current'   => $currentSection,
                'blueprint' => $sections,
                "args"      => [
                    "updateRoute" => [
                        "name"       => "org.models.website.layout.update",
                        'parameters' => $website->id
                    ],
                ],
            ],
        ];
    }
}
