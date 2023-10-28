<?php

namespace App\Events;

use App\Models\Helpers\Upload;
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
    public Upload $data;
    public function __construct(Upload $upload)
    {
        $this->data   = $upload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('uploads.org.' . $this->data->organisation_user_id)
        ];
    }

    public function broadcastAs(): string
    {
        return $this->data->type;
    }
}
