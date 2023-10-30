<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 29 Oct 2023 22:54:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Stubs\Migrations;

use App\Enums\Mail\MailshotStateEnum;
use App\Enums\Mail\MailshotTypeEnum;
use Illuminate\Database\Schema\Blueprint;

trait HasMailshotsStats
{
    public function mailshotStats(Blueprint $table): Blueprint
    {
        $table->unsignedInteger('number_mailshots')->default(0);

        foreach (MailshotTypeEnum::cases() as $case) {
            $table->unsignedInteger("number_mailshots_type_{$case->snake()}")->default(0);
        }

        foreach (MailshotStateEnum::cases() as $case) {
            $table->unsignedInteger("number_mailshots_state_{$case->snake()}")->default(0);
        }

        foreach (MailshotTypeEnum::cases() as $caseType) {
            foreach (MailshotStateEnum::cases() as $caseState) {
                $table->unsignedInteger("number_mailshots_type_{$caseType->snake()}_state_{$caseState->snake()}")->default(0);
            }
        }

        return $table;
    }

    public function undoMailshotStats(Blueprint $table): Blueprint
    {
        $table->dropColumn('number_mailshots');

        foreach (MailshotTypeEnum::cases() as $prospectState) {
            $table->dropColumn("number_mailshots_type_{$prospectState->snake()}");
        }

        foreach (MailshotStateEnum::cases() as $case) {
            $table->dropColumn('number_mailshots_state_'.$case->snake());
        }

        foreach (MailshotTypeEnum::cases() as $caseType) {
            foreach (MailshotStateEnum::cases() as $caseState) {
                $table->dropColumn("number_mailshots_type_{$caseType->snake()}_state_{$caseState->snake()}");
            }
        }


        return $table;
    }
}
