<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 07 Nov 2023 14:08:39 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Enums\CRM\Prospect\ProspectContactedStateEnum;
use App\Enums\CRM\Prospect\ProspectFailStatusEnum;
use App\Enums\CRM\Prospect\ProspectSuccessStatusEnum;
use App\Stubs\Migrations\HasProspectStats;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use HasProspectStats;

    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->string('contacted_state')->default(ProspectContactedStateEnum::NA->value);
            $table->string('fail_status')->default(ProspectFailStatusEnum::NA);
            $table->string('success_status')->default(ProspectSuccessStatusEnum::NA);
            $table->boolean('dont_contact_me')->default(false);
        });

        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $this->prospectsPrepareForStatsVersion2($table);
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->prospectsPrepareForStatsVersion2($table);
        });
        Schema::table('portfolio_website_stats', function (Blueprint $table) {
            $this->prospectsPrepareForStatsVersion2($table);
        });

        Schema::table('organisation_crm_stats', function (Blueprint $table) {
            $this->prospectsStatsVersion2($table);
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->prospectsStatsVersion2($table);
        });
        Schema::table('portfolio_website_stats', function (Blueprint $table) {
            $this->prospectsStatsVersion2($table);
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
            $this->undoProspectsStatsVersion2($table);
        });
        Schema::table('shop_crm_stats', function (Blueprint $table) {
            $this->undoProspectsStatsVersion2($table);
        });
        Schema::table('portfolio_website_stats', function (Blueprint $table) {
            $this->undoProspectsStatsVersion2($table);
        });
    }
};
