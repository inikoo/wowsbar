<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Models\Announcement;
use Lorisleiva\Actions\Concerns\AsController;
use Inertia\Inertia;

class DeliverAnnouncement
{
    use AsController;

    public function handle(Announcement $announcement): Announcement
    {
        return $announcement;
    }

    public function htmlResponse(Announcement $announcement)
    {
        return Inertia::render(
            'DeliverAnnouncement',
            [
                'announcement_data' => $announcement
            ]
        );
    }
}
