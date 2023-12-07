<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 07 Dec 2023 14:06:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Events;

use App\Http\Resources\Helpers\UploadsResource;
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


    public Upload $data;
    public function __construct(Upload $upload)
    {
        $this->data   = $upload;
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('uploads.org.' . $this->data->id)
        ];
    }

    public function broadcastWith(): array
    {
        return UploadsResource::make($this->data)->getArray();
    }

    public function broadcastAs(): string
    {
        return $this->data->type;
    }
}
