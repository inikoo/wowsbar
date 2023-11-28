<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 14:28:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect\UI;

use App\Http\Resources\CRM\ProspectResource;
use App\Models\Leads\Prospect;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsObject;

class GetProspectShowcase
{
    use AsObject;

    public function handle(Prospect $prospect): array
    {
        $prospectColumns = ['created_at', 'deleted_at',
            'last_contacted_at', 'not_interested_at', 'registered_at', 'invoiced_at', 'invoiced_at', 'last_bounced_at'];
        $timelines = [];
        $feeds     = [];

        foreach ($prospect->audits()->orderby('id')->get() as $value) {
            if($value->event == 'created') {
                $feeds[$value->created_at->toISOString()] = [
                    'label'   => 'Prospect ' . $value->event,
                    'description' => null,
                    'comment' => $value->comments
                ];
            } else {
                $feeds[$value->updated_at->toISOString()] = [
                    'label'   => 'The ' . natural_language_join(array_keys($value->new_values)) . ' has been updated',
                    'description' => collect($value->old_values)->map((function ($item, $key) use ($value) {
                        return Str::of($key)->replace('_', ' ')->title() . ' changed from ' . ($item == '' ? 'null' : $item) . ' to ' . $value->new_values[$key];
                    }))->implode(', ') . '.',
                    'comment' => $value->comments
                ];
            }
        }

        foreach ($prospectColumns as $timeline) {
            $timelineKey = Str::replace('_', ' ', $timeline);
            if (!blank($prospect->{$timeline})) {
                $timelines[$prospect->{$timeline}->toISOString()] = [
                    'label' => 'Prospect ' . $timelineKey,
                    'icon'  => $timeline == 'created_at' ? 'fal fa-sparkles' : null
                ];
            }
        }

        $sortedFeed = collect($feeds)->sortBy(function ($value, $key) {
            return $key;
        })->toArray();

        $sortedTimeline = collect($timelines)->sortBy(function ($value, $key) {
            return $key;
        })->toArray();

        return [
            'info'     => ProspectResource::make($prospect)->getArray(),
            'timeline' => $sortedTimeline,
            'feeds'    => $sortedFeed,
            'state'    => $prospect->state
        ];
    }
}
