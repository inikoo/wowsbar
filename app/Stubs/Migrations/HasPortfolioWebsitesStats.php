<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 30 Sep 2023 23:38:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
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
        $table=$this->bannerStats($table);
        $table=$this->prospectsStats($table);

        $table->unsignedSmallInteger('number_snapshots')->default(0);
        foreach (SnapshotStateEnum::cases() as $state) {
            $table->unsignedSmallInteger('number_snapshots_state_'.Str::replace('-', '_', $state->snake()))->default(0);
        }

        return $table;
    }
}
