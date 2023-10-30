<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Http\Resources\HasSelfCall;
use App\Models\Mail\Mailshot;
use Illuminate\Http\Resources\Json\JsonResource;

class MailshotResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Mailshot $mailshot */
        $mailshot = $this;

        return [
            'slug'        => $mailshot->slug,
            'subject'     => $mailshot->subject,
            'state'       => $mailshot->state,
            'state_label' => $mailshot->state->labels()[$mailshot->state->value],
            'state_icon'  => $mailshot->state->stateIcon()[$mailshot->state->value],
            'layout'      => $mailshot->layout,
        ];
    }
}
