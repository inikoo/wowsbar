<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Web;

use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

class WebpageResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Web\Webpage $webpage */
        $webpage = $this;

        return [
            'slug'       => $webpage->slug,
            'level'      => $webpage->level,
            'code'       => $webpage->code,
            'type'       => $webpage->type,
            'typeIcon'   => match ($webpage->type) {
                WebpageTypeEnum::STOREFRONT => ['fal', 'fa-home'],
                WebpageTypeEnum::ENGAGEMENT => ['fal', 'fa-ufo-beam'],
                WebpageTypeEnum::AUTH       => ['fal', 'fa-sign-in'],
                WebpageTypeEnum::BLOG       => ['fal', 'fa-newspaper'],
                default                     => ['fal','fa-browser']
            },
            'purpose'    => $webpage->purpose,
            'created_at' => $webpage->created_at,
            'updated_at' => $webpage->updated_at,
        ];
    }
}
