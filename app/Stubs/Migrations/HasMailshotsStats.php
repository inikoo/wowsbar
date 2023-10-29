<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 29 Oct 2023 22:54:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\Mail\MailshotType;
use App\Enums\Miscellaneous\GenderEnum;
use Illuminate\Database\Schema\Blueprint;

trait HasMailshotsStats
{
    public function prospectsStats(Blueprint $table): Blueprint
    {
        $table->unsignedInteger('number_mailshots')->default(0);

        foreach (MailshotType::cases() as $case) {
            $table->unsignedInteger("number_mailshots_type_{$case->snake()}")->default(0);
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
}
