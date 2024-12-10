<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Enums\Portfolio\Announcement\AnnouncementStatusEnum;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetActiveAnnouncement
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): Collection
    {
        return $portfolioWebsite->announcements()
            ->where('status', AnnouncementStatusEnum::ACTIVE->value)
            ->get();
    }
}
