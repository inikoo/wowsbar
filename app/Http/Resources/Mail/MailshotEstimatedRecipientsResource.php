<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 22:54:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

class MailshotEstimatedRecipientsResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Leads\Prospect $prospect */
        $prospect = $this;

        return [
            'contact_name'      => $prospect->contact_name,
            'email'             => $prospect->email,
            'state'             => $prospect->state,
            'last_contacted_at' => $prospect->last_contacted_at

        ];
    }
}
