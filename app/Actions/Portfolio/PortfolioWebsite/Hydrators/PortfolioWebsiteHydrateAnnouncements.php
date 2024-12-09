<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\Hydrators;

use App\Models\Portfolio\PortfolioWebsite;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebsiteHydrateAnnouncements
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        $stats = [
            'number_announcements' => $portfolioWebsite->announcements()->count(),
        ];

        $portfolioWebsite->stats->update($stats);
    }
}
