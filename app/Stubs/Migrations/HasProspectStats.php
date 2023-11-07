<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 04 Oct 2023 19:55:13 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\CRM\Prospect\ProspectBounceStatusEnum;
use App\Enums\CRM\Prospect\ProspectOutcomeStatusEnum;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\Miscellaneous\GenderEnum;
use Illuminate\Database\Schema\Blueprint;

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

    public function prospectsStatsBis(Blueprint $table): Blueprint
    {
        foreach (ProspectBounceStatusEnum::cases() as $case) {
            $table->unsignedInteger("number_prospects_bounce_status_{$case->snake()}")->default(0);
        }

        foreach (ProspectOutcomeStatusEnum::cases() as $case) {
            $table->unsignedInteger("number_prospects_outcome_status_{$case->snake()}")->default(0);
        }

        $table->unsignedInteger("number_prospects_contacted")->default(0);
        $table->unsignedInteger("number_prospects_not_contacted")->default(0);

        $table->unsignedInteger("number_prospects_dont_contact_me")->default(0);


        return $table;
    }

    public function undoProspectsStatsBis(Blueprint $table): Blueprint
    {
        $table->dropColumn([
            'number_prospects_contacted',
            'number_prospects_not_contacted',
            'number_prospects_dont_contact_me'
        ]);

        foreach (ProspectBounceStatusEnum::cases() as $case) {
            $table->dropColumn("number_prospects_bounce_status_{$case->snake()}");
        }

        foreach (ProspectOutcomeStatusEnum::cases() as $case) {
            $table->dropColumn("number_prospects_outcome_status_{$case->snake()}");
        }

        return $table;
    }


}
