<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 07 Dec 2023 14:06:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Events;

use App\Actions\Web\Website\UI\GetWebsiteWorkshopFooter;
use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Web\Website;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastPreviewHeaderFooter implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $data;
    public Website|PortfolioWebsite $website;

    public function __construct(Website|PortfolioWebsite $website)
    {
        $this->website = $website;
    }

    public function broadcastWith(): array
    {
        return [
            'footer' => GetWebsiteWorkshopFooter::run($this->website),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("footer.preview")
        ];
    }

    public function broadcastAs(): string
    {
        return 'WebpagePreview';
    }
}
