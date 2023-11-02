<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 01 Nov 2023 00:38:31 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Stubs\Migrations\HasCustomerUserStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasCustomerUserStats;
    public function up(): void
    {
        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $this->customerUserStats($table);
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->customerUserStats($table);
        });
        Schema::table('customer_stats', function (Blueprint $table) {
            $this->customerUserStats($table);
            $table->dropColumn('number_users');
            $table->dropColumn('number_users_status_active');
            $table->dropColumn('number_users_status_inactive');
        });
        Schema::table('website_stats', function (Blueprint $table) {
            $this->customerUserStats($table);
        });


    }


    public function down(): void
    {
        Schema::table('website_stats', function (Blueprint $table) {
            $this->undoCustomerUserStats($table);
        });
        Schema::table('customer_stats', function (Blueprint $table) {
            $this->undoCustomerUserStats($table);
            $table->unsignedSmallInteger('number_users')->default(0);
            $table->unsignedSmallInteger('number_users_status_active')->default(0);
            $table->unsignedSmallInteger('number_users_status_inactive')->default(0);
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->undoCustomerUserStats($table);
        });
        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $this->undoCustomerUserStats($table);
        });

    }
};
