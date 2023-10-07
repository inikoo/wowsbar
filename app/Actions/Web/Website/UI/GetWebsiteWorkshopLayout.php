<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\UI;

use App\Actions\Assets\Language\UI\GetLanguagesOptions;
use App\Models\Web\Website;
use Illuminate\Support\Arr;
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
                        "title"   => __("profile"),
                        "icon"    => "fa-light fa-user-circle",
                        "notes"   => __("This information will be synchronised in all your workspaces."),
                        "current" => true,
                        "fields"  => [
                            "about"  => [
                                "type"  => "textarea",
                                "label" => __("about"),
                                "value" => ''
                            ],
                            "avatar" => [
                                "type"  => "avatar",
                                "label" => __("photo"),
                                "value" => ''
                            ],

                        ],
                    ],

                ],
                "args"      => [
                    "updateRoute" => [
                        "name"       => "org.models.website.layout.update"
                    ],
                ],
            ],
        ];
    }
}
