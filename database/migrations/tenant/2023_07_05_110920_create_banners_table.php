<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 14:24:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\Banner\BannerStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->ulid()->index();
            $table->unsignedSmallInteger('tenant_id')->index();
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->unsignedSmallInteger('portfolio_website_id')->nullable()->index();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites');
            $table->string('slug')->collation('und_ns');
            $table->string('code')->collation('und_ns_ci');
            $table->string('name')->collation('und_ns_ci');
            $table->string('state')->default(BannerStateEnum::IN_PROCESS->value);
            $table->dateTimeTz('ready_at')->nullable();
            $table->dateTimeTz('live_at')->nullable();
            $table->dateTimeTz('retired_at')->nullable();
            $table->jsonb('layout');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(['tenant_id','slug']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
