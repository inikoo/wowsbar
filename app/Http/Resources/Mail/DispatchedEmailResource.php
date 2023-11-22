<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 22:54:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Http\Resources\HasSelfCall;
use App\Models\Mail\DispatchedEmail;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchedEmailResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var DispatchedEmail $dispatchedEmail */
        $dispatchedEmail = $this;

        return [
            'id'           => $dispatchedEmail->id,
            'contact_name' => $dispatchedEmail->mailshotRecipient?->recipient?->contact_name,
            'subject'      => $dispatchedEmail->mailshot->subject,
            'email'        => $dispatchedEmail->email->address,
            'state'        => $dispatchedEmail->state,
            'state_label'  => $dispatchedEmail->state->labels()[$dispatchedEmail->state->value],
            'state_icon'   => $dispatchedEmail->state->stateIcon()[$dispatchedEmail->state->value],
            'sent_at'      => $dispatchedEmail->sent_at,
            'delivered_at' => $dispatchedEmail->delivered_at
        ];
    }
}
