<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 13:49:31 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UI;

use App\Http\Resources\HasSelfCall;
use App\Models\Web\Website;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAppResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Website $website */
        $website = $this;

        return [
            'slug' => $website->slug,
            'name' => $website->name,
            'logo' => !blank($website->logo_id) ? $website->logoImageSources(0, 48) : null,
            'url'  => (app()->environment(['local']) ? 'http://' : 'https://').$website->domain
        ];
    }
}
