<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 09:52:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Web;

use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Web\Website $website */
        $website = $this;

        return [
            'id'         => $website->id,
            'slug'       => $website->slug,
            'name'       => $website->name,
            'domain'     => $website->domain,
            'state'      => $website->state,
            'status'     => $website->status,
            'created_at' => $website->created_at,
            'updated_at' => $website->updated_at,
        ];
    }
}
