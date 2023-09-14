<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Web;

use App\Http\Resources\HasSelfCall;
use App\Models\Organisation\Web\Webpage;
use Illuminate\Http\Resources\Json\JsonResource;

class WebpageResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Webpage $webpage */
        $webpage=$this;
        return [
            'slug'               => $webpage->slug,
            'code'               => $webpage->code,
            'type'               => $webpage->type,
            'purpose'            => $webpage->purpose,
            'created_at'         => $webpage->created_at,
            'updated_at'         => $webpage->updated_at,
        ];
    }
}
