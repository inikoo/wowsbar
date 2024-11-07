<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 20 Jun 2024 17:50:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\PortfolioWebsite;

use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Web\WebBlockType;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class SeedDefaultFooterToExistPortfolioWebsites
{
    use AsAction;

    public function handle(): void
    {
        $portfolioWebsites     = PortfolioWebsite::all();
        $defaultFooterTemplate = WebBlockType::where('slug', 'footertheme1')->first();

        foreach ($portfolioWebsites as $portfolioWebsite) {
            UpdatePortfolioWebsite::run($portfolioWebsite, [
                'footer_status' => false
            ]);

            $portfolioWebsite->refresh();

            PublishPortfolioWebsiteMarginal::run($portfolioWebsite, 'footer', [
                'layout' => $defaultFooterTemplate->blueprint
            ]);
        }
    }

    public string $commandSignature = 'web:seed-default-footer';

    public function asCommand(Command $command): int
    {
        $this->handle();

        return 0;
    }
}
