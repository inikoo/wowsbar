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
            'id'              => $dispatchedEmail->id,
            'ulid'            => $dispatchedEmail->ulid,
            'contact_name'    => $dispatchedEmail->mailshotRecipient?->recipient?->contact_name,
            'subject'         => $dispatchedEmail->mailshot?->subject,
            'email'           => $dispatchedEmail->email->address,
            'state'           => $dispatchedEmail->state,
            'state_label'     => $dispatchedEmail->state->labels()[$dispatchedEmail->state->value],
            'state_icon'      => $dispatchedEmail->state->stateIcon()[$dispatchedEmail->state->value],
            'sent_at'         => $dispatchedEmail->sent_at,
            'delivered_at'    => $dispatchedEmail->delivered_at,
            'is_test'         => $dispatchedEmail->is_test,
            'is_unsubscribed' => $dispatchedEmail->is_unsubscribed,
            'is_hard_bounced' => $dispatchedEmail->is_hard_bounced,
            'is_soft_bounced' => $dispatchedEmail->is_soft_bounced,
            'is_spam'         => $dispatchedEmail->is_spam,
            'is_opened'       => $dispatchedEmail->is_opened,
            'is_clicked'      => $dispatchedEmail->is_clicked,
            'is_delivered'    => $dispatchedEmail->is_delivered,
            'is_sent'         => $dispatchedEmail->is_sent,
            'is_rejected'     => $dispatchedEmail->is_rejected,
            'is_error'        => $dispatchedEmail->is_error,

        ];
    }
}
