<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 15:24:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Models\Mail\Mailshot;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $slug
 * @property string $subject
 * @property mixed $number_dispatched_emails
 * @property mixed $number_estimated_dispatched_emails
 * @property mixed $number_dispatched_emails_state_delivered
 * @property mixed $number_dispatched_emails_state_opened
 * @property mixed $number_dispatched_emails_state_hard_bounce
 * @property mixed $number_dispatched_emails_state_soft_bounce
 * @property mixed $number_dispatched_emails_state_clicked
 * @property mixed $number_dispatched_emails_state_unsubscribed
 * @property mixed $number_dispatched_emails_state_spam
 * @property mixed $number_dispatched_emails_state_error
 */
class MailshotsResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Mailshot $mailshot */
        $mailshot = $this;

        $numberBounced = $this->number_dispatched_emails_state_error + $this->number_dispatched_emails_state_hard_bounce + $this->number_dispatched_emails_state_soft_bounce;

        return [
            'slug'                 => $this->slug,
            'subject'              => $this->subject,
            'state'                => $mailshot->state,
            'state_label'          => $mailshot->state->labels()[$mailshot->state->value],
            'state_icon'           => $mailshot->state->stateIcon()[$mailshot->state->value],
            'start_sending_at'     => $mailshot->start_sending_at,
            'sent_at'              => $mailshot->sent_at,
            'recipients_stored_at' => $mailshot->recipients_stored_at,
            'number_recipients'    => $mailshot->start_sending_at ? $this->number_dispatched_emails : $this->number_estimated_dispatched_emails,
            'number_error'         => $this->number_dispatched_emails_state_error,
            'number_bounced'       => $numberBounced,
            'percentage_bounced'   => $mailshot->start_sending_at ?
                percentage($numberBounced, $this->number_dispatched_emails) : null,

            'number_delivered'       => $mailshot->start_sending_at ? $this->number_dispatched_emails_state_delivered : null,
            'number_opened'          => $mailshot->start_sending_at ? $this->number_dispatched_emails_state_opened : null,
            'percentage_opened'      => $mailshot->start_sending_at ?
                percentage($this->number_dispatched_emails_state_clicked, $this->number_dispatched_emails_state_delivered)
                : null,
            'percentage_clicked'     => $mailshot->start_sending_at ?
                percentage($this->number_dispatched_emails_state_opened, $this->number_dispatched_emails_state_delivered)
                : null,
            'percentage_unsubscribe' => $mailshot->start_sending_at ?
                percentage($this->number_dispatched_emails_state_delivered, $this->number_dispatched_emails_state_unsubscribed)
                : null,
            'percentage_spam'        => $mailshot->start_sending_at ?
                percentage($this->number_dispatched_emails_state_delivered, $this->number_dispatched_emails_state_spam)
                : null,

        ];
    }
}
