<?php

namespace App\Events;

use App\Models\Tenancy\Tenant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadWebsiteProgressEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public Tenant $tenant;
    public array $data;
    public function __construct($tenant, $data)
    {
        $this->data   = $data;
        $this->tenant = $tenant;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('uploads.' . $this->tenant->slug),
        ];
    }

    public function broadcastAs(): string
    {
        return 'WebsiteUpload';
    }
}
