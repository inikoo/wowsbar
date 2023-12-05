<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $live_snapshot_id
 */
class EmailTemplateResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Mail\EmailTemplate $emailTemplate */
        $emailTemplate = $this;

        return [
            'slug'  => $emailTemplate->slug,
            'title' => $emailTemplate->title,
            'compiled' => $emailTemplate->compiled
        ];
    }
}
