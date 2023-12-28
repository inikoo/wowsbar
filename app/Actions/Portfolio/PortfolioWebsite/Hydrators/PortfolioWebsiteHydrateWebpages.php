<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 24 Dec 2023 21:15:41 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite\Hydrators;

use App\Models\Portfolio\PortfolioWebsite;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Lorisleiva\Actions\Concerns\AsAction;

class PortfolioWebsiteHydrateWebpages
{
    use AsAction;

    private PortfolioWebsite $portfolioWebsite;
    public function __construct(PortfolioWebsite $portfolioWebsite)
    {
        $this->portfolioWebsite = $portfolioWebsite;
    }

    public function getJobMiddleware(): array
    {
        return [(new WithoutOverlapping($this->portfolioWebsite->id))->dontRelease()];
    }

    public function handle(PortfolioWebsite $portfolioWebsite): void
    {
        $stats = [
            'number_portfolio_webpages' => $portfolioWebsite->portfolioWebpages()->count(),
        ];


        $portfolioWebsite->stats->update($stats);
    }
}
