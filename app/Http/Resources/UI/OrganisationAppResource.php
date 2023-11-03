<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 13:49:31 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UI;

use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationAppResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Organisation\Organisation $organisation */
        $organisation = $this;

        return [
            'name' => $organisation->name,
            'logo' => !blank($organisation->logo_id) ? $organisation->logoImageSources(0, 48) : null
        ];
    }
}
