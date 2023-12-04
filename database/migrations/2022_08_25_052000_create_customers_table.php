<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 27 Sep 2023 18:30:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Enums\CRM\Customer\CustomerStatusEnum;
use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Stubs\Migrations\HasContact;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasContact;
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns')->nullable();
            $table->string('reference')->nullable()->unique()->collation('und_ns')->comment('customer public id');
            $table->string('name', 256)->nullable()->collation('und_ns');
            $table = $this->contactFields(table: $table, withWebsite: true);
            $table->jsonb('location');
            $table->string('status')->index()->default(CustomerStatusEnum::PENDING_APPROVAL->value);
            $table->string('state')->index()->default(CustomerStateEnum::REGISTERED->value);
            $table->string('trade_state')->index()->default(CustomerTradeStateEnum::NONE->value)->comment('number of invoices');
            $table->jsonb('data');
            $table->unsignedSmallInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedSmallInteger('website_id')->nullable()->index();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->unsignedSmallInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedSmallInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->ulid('ulid')->index()->unique();
            $table->timestampsTz();
            $this->softDeletes($table);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
