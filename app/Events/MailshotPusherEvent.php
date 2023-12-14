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
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class MailshotPusherEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    private Mailshot $mailshot;

    public function __construct(Mailshot $mailshot)
    {
        $this->data = 'hello';

    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('org.general')
        ];
    }

    public function broadcastAs(): string
    {
        return 'prospects.dashboard';
    }

}
