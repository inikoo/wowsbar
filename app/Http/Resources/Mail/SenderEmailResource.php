<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 09 Nov 2023 22:54:26 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Http\Resources\HasSelfCall;
use App\Models\Mail\SenderEmail;
use Illuminate\Http\Resources\Json\JsonResource;

class SenderEmailResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var SenderEmail $senderEmail */
        $senderEmail = $this;

        return [
            'email_address'                  => $senderEmail->email_address,
            'state'                          => $senderEmail->state,
            'last_verification_submitted_at' => $senderEmail->last_verification_submitted_at,
            'verified_at'                    => $senderEmail->verified_at,
            'id'                             => $senderEmail->id,
            'message'                        => $senderEmail->state->message()[$senderEmail->state->value],

        ];
    }
}
