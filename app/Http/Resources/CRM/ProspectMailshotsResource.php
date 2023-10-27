<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 15:59:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $slug
 */
class ProspectMailshotsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'slug'       => $this->slug,

        ];
    }
}
