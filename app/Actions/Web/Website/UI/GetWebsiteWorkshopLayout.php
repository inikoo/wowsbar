<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\UI;

use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsObject;

class GetWebsiteWorkshopLayout
{
    use AsObject;

    public function handle(Website $website): array
    {
        return [
            "formData" => [
                "blueprint" => [
                    [
                        "title"   => __("art"),
                        "icon"    => "fal fa-images",
                        "current" => true,
                        "fields"  => [
                            "logo" => [
                                "type"  => "avatar",
                                "label" => __("logo"),
                                "value" => !blank($website->logo_id) ? $website->logoImageSources(320, 320) : null,

                            ],

                        ],
                    ],

                ],
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
