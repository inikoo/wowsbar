<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:04:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\Web\WebBlockType\WebBlockTypeSlugEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {

    public function up(): void
    {
        Schema::create('tenant_portfolio_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');


            $table->unsignedSmallInteger('number_banners')->default(0);

            foreach (BannerStateEnum::cases() as $state) {
                $table->unsignedSmallInteger('number_banners_state_' . Str::replace('-', '_', $state->snake()))->default(0);
            }

            /*

            foreach (WebBlockTypeSlugEnum::cases() as $type) {
                $table->unsignedSmallInteger('number_content_blocks_type_' .
                    Str::replace('-', '_', $type->snake()))->default(0);
                foreach (BannerStateEnum::cases() as $state) {
                    $table->unsignedSmallInteger(
                        'number_content_blocks_type_'.
                        Str::replace('-', '_', $type->snake()).
                        '_state_' .
                        Str::replace('-', '_', $state->snake())
                    )->default(0);
                }

            }
            */


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_portfolio_stats');
    }
};
