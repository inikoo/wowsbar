<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 11 Mar 2023 04:46:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\CRM\Prospect\ProspectStateEnum;
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
        Schema::create('prospects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns')->nullable();
            $table->string('scope_type')->index();
            $table->unsignedInteger('scope_id')->index();
            $table->unsignedInteger('shop_id')->index()->nullable();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('portfolio_website_id')->index()->nullable();
            $table->foreign('portfolio_website_id')->references('id')->on('portfolio_websites');
            $table->string('name', 256)->nullable()->collation('und_ns');
            $table = $this->contactFields(table: $table, withWebsite: true);
            $table->jsonb('location');
            $table->string('state')->index()->default(ProspectStateEnum::NO_CONTACTED->value);
            $table->jsonb('data');
            $table->timestampsTz();
            $table=$this->softDeletes($table);
            $table->unique(['scope_type','scope_id','email']);
            $table->unique(['scope_type','scope_id','phone']);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
