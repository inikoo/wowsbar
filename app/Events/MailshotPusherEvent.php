<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:43:35 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Events;

use App\Http\Resources\Mail\MailshotResource;
use App\Models\Mail\Mailshot;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MailshotPusherEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    private Mailshot $mailshot;

    public function __construct(Mailshot $mailshot)
    {
        $this->mailshot = $mailshot;
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('mailshot.'.$this->mailshot->slug)
        ];
    }

    public function broadcastWith(): array
    {
        return ['mailshot' => MailshotResource::make($this->mailshot)->getArray()];
    }


    public function broadcastAs(): string
    {
        return 'hydrate.sent.emails';
    }
}
