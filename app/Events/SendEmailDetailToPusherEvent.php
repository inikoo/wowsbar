<?php

namespace App\Events;

use App\Models\Mail\MailshotStats;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendEmailDetailToPusherEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public MailshotStats $mailshotStats;

    public function __construct(MailshotStats $mailshotStats)
    {
        $this->mailshotStats = $mailshotStats;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('mailshot.')
        ];
    }

    public function broadcastAs(): string
    {
        return 'hydrate.sent.emails';
    }
}
