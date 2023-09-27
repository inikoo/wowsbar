<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 29 Aug 2022 12:29:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('customer_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedSmallInteger('number_users')->default(0);
            $table->unsignedSmallInteger('number_users_status_active')->default(0);
            $table->unsignedSmallInteger('number_users_status_inactive')->default(0);


            $table->unsignedSmallInteger('number_portfolio_websites')->default(0);
            $table->unsignedSmallInteger('number_images')->default(0);
            $table->unsignedBigInteger('filesize_images')->default(0);
            $table->unsignedSmallInteger('number_attachments')->default(0);
            $table->unsignedBigInteger('filesize_attachments')->default(0);


            $table->timestampTz('last_submitted_order_at')->nullable();
            $table->timestampTz('last_invoiced_at')->nullable();

            $table->unsignedInteger('number_invoices')->default(0);
            $table->unsignedInteger('number_invoices_type_invoice')->default(0);
            $table->unsignedInteger('number_invoices_type_refund')->default(0);
            $table->timestampsTz();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('customer_stats');
    }
};
