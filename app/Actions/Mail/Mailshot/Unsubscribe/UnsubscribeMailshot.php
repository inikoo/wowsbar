<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Unsubscribe;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Models\Mail\DispatchedEmail;
use Inertia\Inertia;
use Inertia\Response;

class UnsubscribeMailshot
{
    use WithActionUpdate;

    public function handle(DispatchedEmail $dispatchedEmail): DispatchedEmail
    {
        if ($dispatchedEmail->is_test) {
            return $dispatchedEmail;
        }

        $dispatchedEmail->mailshotRecipient->recipient->update(['state' => ProspectStateEnum::NOT_INTERESTED]);

        return $this->update($dispatchedEmail, ['state' => DispatchedEmailStateEnum::UNSUBSCRIBED]);
    }

    public function asController(DispatchedEmail $dispatchedEmail): DispatchedEmail
    {
        return $this->handle($dispatchedEmail);
    }

    public function htmlResponse(DispatchedEmail $dispatchedEmail): Response
    {
        return Inertia::render('Utils/Unsubscribe', [
            'title'       => __("Unsubscribe from mailshot"),
            'pageHead'    => [
                'title'     => 'Unsubscribe',
                'icon'      => [
                    'tooltip' => __('mailshot'),
                    'icon'    => 'fal fa-sign'
                ],
            ],
            'mailshot' => $dispatchedEmail,
            'message'  => [
                'title'       => __('Unsubscribe successfully.'),
                'description' => __("You have already unsubscribed from this mailshot, We're sorry to see you go."),
                'caution'     => match ($dispatchedEmail->is_test) {
                    true    => __("This is a test mailshot, no action was taken and you can ignore this message."),
                    default => null
                }
            ]
        ]);
    }
}
