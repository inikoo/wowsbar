<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 23 Aug 2023 15:12:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Helpers\Snapshot\SnapshotStateEnum;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\Portfolio\Banner\BannerTypeEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

trait HasBannerStats
{
    public function bannerStats(Blueprint $table): Blueprint
    {
        $table->unsignedSmallInteger('number_banners')->default(0);
        $table->unsignedSmallInteger('number_historic_snapshots')->default(0);

        foreach (BannerTypeEnum::cases() as $case) {
            $table->unsignedSmallInteger('number_banners_type_' . Str::replace('-', '_', $case->snake()))->default(0);
        }

        foreach (BannerStateEnum::cases() as $case) {
            $table->unsignedSmallInteger('number_banners_state_' . Str::replace('-', '_', $case->snake()))->default(0);
        }

        $table->unsignedSmallInteger('number_banner_snapshots')->default(0);
        foreach (SnapshotStateEnum::cases() as $state) {
            $table->unsignedSmallInteger('number_banners_snapshots_state_'.Str::replace('-', '_', $state->snake()))->default(0);
        }



        return $table;
    }
}
