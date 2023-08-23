<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 23 Aug 2023 15:12:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

trait HasPortfolioStats
{
    public function portfolioStats(Blueprint $table): Blueprint
    {


        $table->unsignedSmallInteger('number_banners')->default(0);

        foreach (BannerStateEnum::cases() as $state) {
            $table->unsignedSmallInteger('number_banners_state_' . Str::replace('-', '_', $state->snake()))->default(0);
        }


        return $table;
    }
}
