<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 15:13:41 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Http\Resources\HasSelfCall;
use App\Models\Mail\MailshotStats;
use Illuminate\Http\Resources\Json\JsonResource;

class MailshotStatResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var MailshotStats $mailshotStatsStats */
        $mailshotStatsStats = $this;

        return [
           'number_estimated_dispatched_emails'         => $mailshotStatsStats->number_estimated_dispatched_emails,
           'number_dispatched_emails'                   => $mailshotStatsStats->number_dispatched_emails,
           'number_dispatched_emails_state_ready'       => $mailshotStatsStats->number_dispatched_emails_state_ready,
           'number_dispatched_emails_state_error'       => $mailshotStatsStats->number_dispatched_emails_state_error,
           'number_dispatched_emails_state_rejected'    => $mailshotStatsStats->number_dispatched_emails_state_rejected,
           'number_dispatched_emails_state_sent'        => $mailshotStatsStats->number_dispatched_emails_state_sent,
           'number_dispatched_emails_state_delivered'   => $mailshotStatsStats->number_dispatched_emails_state_delivered,
           'number_dispatched_emails_state_hard_bounce' => $mailshotStatsStats->number_dispatched_emails_state_hard_bounce,
           'number_dispatched_emails_state_soft_bounce' => $mailshotStatsStats->number_dispatched_emails_state_soft_bounce,
           'number_dispatched_emails_state_opened'      => $mailshotStatsStats->number_dispatched_emails_state_opened,
           'number_dispatched_emails_state_clicked'     => $mailshotStatsStats->number_dispatched_emails_state_clicked,
           'number_dispatched_emails_state_spam'        => $mailshotStatsStats->number_dispatched_emails_state_spam,
           'number_dispatched_emails_state_unsubscribed'=> $mailshotStatsStats->number_dispatched_emails_state_unsubscribed,

        ];
    }
}
