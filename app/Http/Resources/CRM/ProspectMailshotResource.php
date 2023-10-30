<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 13:37:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $subject
 */
class ProspectMailshotResource extends JsonResource
{
    use HasSelfCall;
    public function toArray($request): array
    {
        return [
            'slug'    => $this->slug,
            'subject' => $this->subject

        ];
    }
}
