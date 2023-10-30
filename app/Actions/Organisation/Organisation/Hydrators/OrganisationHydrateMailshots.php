<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Enums\Mail\MailshotStateEnum;
use App\Enums\Mail\MailshotTypeEnum;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Models\Mail\Mailshot;
use App\Models\Portfolio\Banner;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateMailshots
{
    use AsAction;


    public function handle(): void
    {
        $stats = [
            'number_mailshots'            => Mailshot::count(),
        ];

        foreach (MailshotTypeEnum::cases() as $case) {
            $stats["number_mailshots_type_{$case->snake()}"] = Mailshot::where('type', $case->value)->count();
        }

        foreach (MailshotStateEnum::cases() as $case) {
            $stats["number_mailshots_state_{$case->snake()}"] = Mailshot::where('state', $case->value)->count();;
        }

        foreach (MailshotTypeEnum::cases() as $caseType) {
            foreach (MailshotStateEnum::cases() as $caseState) {
                $stats["number_mailshots_type_{$caseType->snake()}_state_{$caseState->snake()}"] = Mailshot::where([['type', $caseState->value], ['state', $caseType->value]])->count();;
            }
        }


        organisation()->mailStats()->update($stats);
    }

}
