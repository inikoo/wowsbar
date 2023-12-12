<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\AwsEmail;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Mail\SenderEmail;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckPendingSenderEmails
{
    use WithActionUpdate;
    use AwsClient;
    use AsAction;

    public function handle(): void
    {
        SenderEmail::whereNull('verified_at')->get()->each(function (SenderEmail $senderEmail) {
            CheckSenderEmailVerification::dispatch($senderEmail);
        });
    }
}
