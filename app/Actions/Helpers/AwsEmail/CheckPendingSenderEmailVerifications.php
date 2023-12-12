<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Dec 2023 13:14:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\AwsEmail;

use App\Actions\Mail\EmailAddress\Traits\AwsClient;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Mail\SenderEmail\SenderEmailStateEnum;
use App\Models\Mail\SenderEmail;
use Lorisleiva\Actions\Concerns\AsCommand;

class CheckPendingSenderEmailVerifications
{
    use WithActionUpdate;
    use AwsClient;
    use AsCommand;


    public function handle(): void
    {

        SenderEmail::where('state', SenderEmailStateEnum::PENDING)->get()->each(function (SenderEmail $senderEmail) {
            CheckSenderEmailVerification::dispatch($senderEmail);
        });
    }

    public string $commandSignature = 'aws:check-pending-sender-email-verifications}';


    public function asCommand(): int
    {
        $this->handle();


        return 0;
    }
}
