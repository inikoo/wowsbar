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
use Illuminate\Support\Str;

class MailshotResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var Mailshot $mailshot */
        $mailshot = $this;

        $timelines    = [];
        $timelineData = ['schedule_at', 'ready_at', 'sent_at', 'cancelled_at', 'stopped_at', 'created_at'];

        foreach ($timelineData as $timeline) {
            $timelineKey = Str::replace('_at', '', $timeline);
            if (!blank($mailshot->{$timeline})) {
                $timelines[$mailshot->{$timeline}->toISOString()] = [
                    'label' => 'Mailshot ' . $timelineKey,
                    'icon'  => $timeline == 'created_at' ? 'fal fa-sparkles' : $mailshot->state->stateIcon()[$timelineKey]['icon'],
                ];
            }
        }

        $sortedTimeline = collect($timelines)->sortBy(function ($value, $key) {
            return $key;
        })->toArray();

        return [
            'slug'                => $mailshot->slug,
            'subject'             => $mailshot->subject,
            'state'               => $mailshot->state,
            'state_label'         => $mailshot->state->labels()[$mailshot->state->value],
            'state_icon'          => $mailshot->state->stateIcon()[$mailshot->state->value],
            'stats'               => MailshotStatResource::make($mailshot->mailshotStats)->getArray(),
            'recipient_stored_at' => $mailshot->recipients_stored_at,
            'schedule_at'         => $mailshot->schedule_at,
            'ready_at'            => $mailshot->ready_at,
            'sent_at'             => $mailshot->sent_at,
            'cancelled_at'        => $mailshot->cancelled_at,
            'stopped_at'          => $mailshot->stopped_at,
            'date'                => $mailshot->date,
            'created_at'          => $mailshot->created_at,
            'updated_at'          => $mailshot->updated_at,
            'timeline'            => $sortedTimeline
        ];
    }
}
