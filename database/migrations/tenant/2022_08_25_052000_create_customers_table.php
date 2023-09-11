<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 29 Aug 2022 12:29:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

use App\Enums\CRM\Customer\CustomerStateEnum;
use App\Enums\CRM\Customer\CustomerStatusEnum;
use App\Enums\CRM\Customer\CustomerTradeStateEnum;
use App\Stubs\Migrations\HasAssets;
use App\Stubs\Migrations\HasContact;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasContact;
    use HasAssets;

    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedSmallInteger('tenant_id')->index()->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants');

            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('reference')->nullable()->collation('und_ns')->comment('customer public id');
            $table->string('name', 256)->nullable()->collation('und_ns');

            $table->string('username', 256)->nullable()->collation('und_ns');
            $table->string('password')->nullable()->collation('und_ns');

            $table = $this->contactFields(table: $table, withWebsite: true);
            $table = $this->assets($table);
            $table->jsonb('location');
            $table->string('status')->index()->default(CustomerStatusEnum::PENDING_APPROVAL->value);
            $table->string('state')->index()->default(CustomerStateEnum::IN_PROCESS->value);
            $table->string('trade_state')->index()->default(CustomerTradeStateEnum::NONE->value)->comment('number of invoices');
            $table->boolean('is_fulfilment')->index()->default(false);
            $table->boolean('is_dropshipping')->index()->default(false);
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unsignedInteger('source_id')->nullable()->unique();
            $table->unique(['reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
