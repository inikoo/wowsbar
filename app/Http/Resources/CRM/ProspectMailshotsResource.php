<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 27 Oct 2023 15:59:16 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Models\Mail\Mailshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $subject
 * @property mixed $number_dispatched_emails
 * @property mixed $number_estimated_dispatched_emails
 */
class ProspectMailshotsResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Mailshot $mailshot */
        $mailshot = $this;

        return [
            'slug'              => $this->slug,
            'subject'           => $this->subject,
            'state'             => $mailshot->state,
            'state_label'       => $mailshot->state->labels()[$mailshot->state->value],
            'state_icon'        => $mailshot->state->stateIcon()[$mailshot->state->value],
            'number_recipients' => $mailshot->start_sending_at
                ?
                $this->number_dispatched_emails
                :
                $this->number_estimated_dispatched_emails

        ];
    }
}
