<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 13:33:09 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Market\Shop\ShopStateEnum;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('code')->unique()->collation('und_ns');
            $table->string('name')->collation('und_ns');
            $table->string('company_name', 256)->nullable()->collation('und_ns');
            $table->string('contact_name', 256)->nullable()->collation('und_ns');
            $table->string('email')->nullable()->collation('und_ns');
            $table->string('phone')->nullable()->collation('und_ns');
            $table->string('identity_document_type')->nullable();
            $table->string('identity_document_number')->nullable()->collation('und_ns');
            $table->unsignedInteger('address_id')->nullable()->index();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->jsonb('location');
            $table->string('state')->index()->default(ShopStateEnum::IN_PROCESS->value);
            $table->string('type')->index();
            $table->date('open_at')->nullable();
            $table->date('closed_at')->nullable();
            $table->unsignedSmallInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedSmallInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->unsignedSmallInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->unsignedSmallInteger('timezone_id');
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->jsonb('data');
            $table->jsonb('settings');
            $table->unsignedSmallInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onUpdate('cascade')->onDelete('cascade');
            $table->timestampsTz();
            $table=$this->softDeletes($table);
        });
        DB::statement("CREATE INDEX ON shops (lower('code')) ");

    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
