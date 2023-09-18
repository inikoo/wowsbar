<?php

namespace App\Events;

use App\Models\Tenancy\Tenant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadExcelProgressEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public Tenant|null $tenant;
    public array $data;
    public string $event;
    public function __construct($tenant, $data, $event = 'WebsiteUpload')
    {
        $this->data   = $data;
        $this->event  = $event;
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
            new Channel('uploads.' . $this->tenant->slug ?? 'org'),
        ];
    }

    public function broadcastAs(): string
    {
        return $this->event;
    }
}
