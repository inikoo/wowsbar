<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 04 Oct 2023 19:55:13 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\CRM\Prospect\ProspectContactedStateEnum;
use App\Enums\CRM\Prospect\ProspectFailStatusEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\CRM\Prospect\ProspectSuccessStatusEnum;
use App\Enums\Miscellaneous\GenderEnum;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait HasProspectStats
{
    public function prospectsStats(Blueprint $table): Blueprint
    {
        $table->unsignedInteger('number_prospects')->default(0);

        foreach (ProspectStateEnum::cases() as $prospectState) {
            $table->unsignedInteger("number_prospects_state_{$prospectState->snake()}")->default(0);
        }

        foreach (GenderEnum::cases() as $case) {
            $table->unsignedSmallInteger('number_prospects_gender_'.$case->snake())->default(0);
        }


        return $table;
    }

    public function undoProspectsStats(Blueprint $table): Blueprint
    {
        $table->dropColumn('number_prospects');

        foreach (ProspectStateEnum::cases() as $prospectState) {
            $table->dropColumn("number_prospects_state_{$prospectState->snake()}");
        }

        foreach (GenderEnum::cases() as $case) {
            $table->dropColumn('number_prospects_gender_'.$case->snake());
        }

        return $table;
    }

    public function prospectsPrepareForStatsVersion2(Blueprint $table): Blueprint
    {
        foreach([
            'number_prospects_state_no_contacted',
            'number_prospects_state_contacted',
            'number_prospects_state_not_interested',
            'number_prospects_state_registered',
            'number_prospects_state_invoiced',
            'number_prospects_state_bounced',
            'number_prospects_state_fail',
            'number_prospects_state_success',
        ] as $column) {
            if (Schema::hasColumn($table->getTable(), $column)) {
                $table->dropColumn($column);
            }

        }
        return $table;
    }

    public function prospectsStatsVersion2(Blueprint $table): Blueprint
    {

        foreach (ProspectStateEnum::cases() as $case) {
            $table->unsignedInteger("number_prospects_state_{$case->snake()}")->default(0);
        }

        foreach (ProspectContactedStateEnum::cases() as $case) {
            $table->unsignedInteger("number_prospects_contacted_state_{$case->snake()}")->default(0);
        }

        foreach (ProspectFailStatusEnum::cases() as $case) {
            $table->unsignedInteger("number_prospects_fail_status_{$case->snake()}")->default(0);
        }

        foreach (ProspectSuccessStatusEnum::cases() as $case) {
            $table->unsignedInteger("number_prospects_success_status_{$case->snake()}")->default(0);
        }


        $table->unsignedInteger("number_prospects_dont_contact_me")->default(0);


        return $table;
    }

    public function undoProspectsStatsVersion2(Blueprint $table): Blueprint
    {


        foreach (ProspectStateEnum::cases() as $case) {
            $table->dropColumn("number_prospects_state_{$case->snake()}");
        }

        foreach (ProspectContactedStateEnum::cases() as $case) {
            $table->dropColumn("number_prospects_contacted_state_{$case->snake()}");
        }

        foreach (ProspectFailStatusEnum::cases() as $case) {
            $table->dropColumn("number_prospects_fail_status_{$case->snake()}");
        }

        foreach (ProspectSuccessStatusEnum::cases() as $case) {
            $table->dropColumn("number_prospects_success_status_{$case->snake()}");
        }

        /*
        $table->unsignedInteger("number_prospects_state_no_contacted")->default(0);
        $table->unsignedInteger("number_prospects_state_contacted")->default(0);
        $table->unsignedInteger("number_prospects_state_not_interested")->default(0);
        $table->unsignedInteger("number_prospects_state_registered")->default(0);
        $table->unsignedInteger("number_prospects_state_invoiced")->default(0);
        $table->unsignedInteger("number_prospects_state_bounced")->default(0);
*/
        return $table;
    }


}
