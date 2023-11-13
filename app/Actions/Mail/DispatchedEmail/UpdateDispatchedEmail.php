<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 00:37:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\DispatchedEmail;

use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateDispatchedEmails;
use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateErrorEmails;
use App\Actions\Mail\Mailshot\Hydrators\MailshotHydrateSentEmails;
use App\Actions\Traits\WithActionUpdate;
use App\Models\Mail\DispatchedEmail;

class UpdateDispatchedEmail
{
    use WithActionUpdate;


    public function handle(DispatchedEmail $dispatchedEmail, array $modelData): DispatchedEmail
    {

        $dispatchedEmail = $this->update($dispatchedEmail, $modelData, ['data']);

        if ($dispatchedEmail->mailshot_id) {
            if ($dispatchedEmail->wasChanged(['is_sent'])) {
                MailshotHydrateSentEmails::dispatch($dispatchedEmail->mailshot);
            }

            if ($dispatchedEmail->wasChanged(['is_error'])) {
                MailshotHydrateErrorEmails::dispatch($dispatchedEmail->mailshot);
            }

            if ($dispatchedEmail->wasChanged(['state'])) {
                MailshotHydrateDispatchedEmails::dispatch($dispatchedEmail->mailshot);
            }
        }


        return $dispatchedEmail;
    }


}
