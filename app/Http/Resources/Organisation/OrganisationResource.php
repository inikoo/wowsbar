<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 12:10:29 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Organisation;

use App\Http\Resources\HasSelfCall;
use App\Models\Organisation\Organisation;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Organisation $organisation */
        $organisation=$this;

        return [
            'id'            => $organisation->id,
            'code'          => $organisation->code,
            'name'          => $organisation->name,

        ];
    }

}
