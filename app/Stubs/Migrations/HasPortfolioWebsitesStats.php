<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 30 Sep 2023 23:38:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Helpers\Interest\InterestEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

trait HasPortfolioWebsitesStats
{
    use HasBannerStats;
    use HasProspectStats;

    public function portfolioWebsiteStats(Blueprint $table): Blueprint
    {
        $table->unsignedSmallInteger('number_portfolio_websites')->default(0);
        $table->unsignedSmallInteger('number_banners_no_website')->default(0);

        foreach (json_decode(file_get_contents(base_path('database/seeders/datasets/divisions.json')), true) as $division) {
            $table->unsignedSmallInteger('number_portfolio_websites_division_' . $division['slug'])->default(0);

            foreach (InterestEnum::cases() as $case) {
                $table->unsignedSmallInteger('number_portfolio_websites_' . $division['slug'] . '_' . Str::replace('-', '_', $case->snake()))->default(0);
            }
        }

        $table=$this->bannerStats($table);
        return $this->prospectsStats($table);
    }
}
