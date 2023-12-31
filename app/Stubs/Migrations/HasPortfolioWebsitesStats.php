<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 30 Sep 2023 23:38:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Divisions\DivisionEnum;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteInterestEnum;
use Illuminate\Database\Schema\Blueprint;

trait HasPortfolioWebsitesStats
{
    use HasBannerStats;
    use HasProspectStats;

    public function portfolioWebsiteStats(Blueprint $table): Blueprint
    {
        $table->unsignedSmallInteger('number_portfolio_websites')->default(0);
        $table->unsignedSmallInteger('number_banners_no_website')->default(0);


        foreach (DivisionEnum::cases() as $division) {
            $table->unsignedSmallInteger('number_portfolio_websites_division_' . $division->snake())->default(0);

            foreach (PortfolioWebsiteInterestEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_portfolio_websites_' . $division->snake() . '_' . $case->snake())->default(0);
            }

        }


        $table=$this->bannerStats($table);
        return $this->prospectsStats($table);
    }
}
