<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 14:24:05 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Enums\Portfolio\Banner\BannerTypeEnum;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->ulid()->index();
            $table->string('type')->default(BannerTypeEnum::LANDSCAPE->value);
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedSmallInteger('portfolio_website_id')->nullable()->index();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('name')->collation('und_ns_ci');
            $table->string('state')->default(BannerStateEnum::UNPUBLISHED->value);
            $table->unsignedSmallInteger('unpublished_snapshot_id')->nullable()->index();
            $table->unsignedSmallInteger('live_snapshot_id')->nullable()->index();
            $table->dateTimeTz('date')->index();
            $table->dateTimeTz('live_at')->nullable();
            $table->dateTimeTz('switch_off_at')->nullable();
            $table->jsonb('compiled_layout');
            $table->jsonb('data');
            $table->unsignedInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('media');
            $table->timestampsTz();
            $table=$this->softDeletes($table);
            $table->unique(['customer_id', 'slug']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
