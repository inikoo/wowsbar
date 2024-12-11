<?php

namespace App\Actions\Portfolio\Announcement\UI;

use App\Enums\Portfolio\Announcement\AnnouncementStatusEnum;
use App\Models\Announcement;
use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\QueryBuilder\QueryBuilder;

class GetActiveAnnouncement
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): Collection
    {
        $queryBuilder = QueryBuilder::for(Announcement::class);

        return $queryBuilder
            ->where('portfolio_website_id', $portfolioWebsite->id)
            ->where('status', AnnouncementStatusEnum::ACTIVE->value)
            ->get();
    }
}
