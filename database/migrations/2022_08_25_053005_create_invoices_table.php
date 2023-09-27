<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 13:42:56 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasSalesTransactionParents;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSalesTransactionParents;
    public function up(): void
    {

        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('number')->index();
            $table=$this->salesTransactionParents($table);
            $table->string('type')->index();
            $table->unsignedSmallInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('public.currencies');
            $table->decimal('exchange', 16, 6)->default(1);
            $table->decimal('net', 16)->default(0);
            $table->decimal('total', 16)->default(0);
            $table->decimal('payment', 16)->default(0);
            $table->dateTimeTz('paid_at')->nullable();
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
