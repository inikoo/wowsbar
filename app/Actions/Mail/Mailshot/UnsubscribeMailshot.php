<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Mailshot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class UnsubscribeMailshot
{
    use WithActionUpdate;

    public function handle(DispatchedEmail $dispatchedEmail): DispatchedEmail
    {
        if (Arr::get($dispatchedEmail->data, 'is_test', false)) {
            return $dispatchedEmail;
        }

        return $this->update($dispatchedEmail, ['state' => DispatchedEmailStateEnum::UNSUBSCRIBED]);
    }

    public function htmlResponse(DispatchedEmail $dispatchedEmail): Response
    {
        return Inertia::render('Utils/Unsubscribe', [
            'mailshot' => $dispatchedEmail,
            'message' => match ($dispatchedEmail->state) {
                DispatchedEmailStateEnum::UNSUBSCRIBED => 'You have already unsubscribed from this mailshot',
                default => 'You have been unsubscribed from this mailshot'
            }
        ]);
    }
}
