<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 07 Nov 2023 14:08:39 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\CRM\Prospect\ProspectContactStateEnum;
use App\Stubs\Migrations\HasProspectStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasProspectStats;

    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->string('contact_state')->default(ProspectContactStateEnum::NO_CONTACTED->value);
            $table->string('outcome_status')->nullable();
            $table->string('bounce_status')->nullable();
            $table->boolean('dont_contact_me')->default(false);
        });

        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $this->prospectsStatsBis($table);
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->prospectsStatsBis($table);
        });
        Schema::table('portfolio_website_stats', function (Blueprint $table) {
            $this->prospectsStatsBis($table);
        });

    }


    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn([
                'contact_state',
                'outcome_status',
                'bounce_status',
                'not_interested'
            ]);
        });

        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $this->undoProspectsStatsBis($table);
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->undoProspectsStatsBis($table);
        });
        Schema::table('portfolio_website_stats', function (Blueprint $table) {
            $this->undoProspectsStatsBis($table);
        });
    }
};
