<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 28 Feb 2023 14:18:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\Accounting\Payment\PaymentStateEnum;
use App\Enums\Accounting\Payment\PaymentStatusEnum;
use App\Stubs\Migrations\HasSoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasSoftDeletes;
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedSmallInteger('payment_account_id')->index();
            $table->foreign('payment_account_id')->references('id')->on('payment_accounts');
            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->string('type');
            $table->string('reference')->index()->collation('und_ns');
            $table->string('webhook_id')->index()->collation('und_ns')->nullable();
            $table->string('slug')->unique()->collation('und_ns');
            $table->string('status')->index()->default(PaymentStatusEnum::IN_PROCESS->value);
            $table->string('state')->index()->default(PaymentStateEnum::APPROVING->value);
            $table->string('subsequent_status')->index()->nullable();
            $table->decimal('amount', 18);
            $table->decimal('org_amount', 18);
            $table->jsonb('data');
            $table->dateTimeTz('date')->index()->comment('Most relevant date at current state');
            $table->dateTimeTz('completed_at')->nullable();
            $table->dateTimeTz('cancelled_at')->nullable();
            $table->timestampsTz();
            $table=$this->softDeletes($table);
            $table->boolean('with_refund')->default(false);

            $table->unique(['reference']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
