<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\UI;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Models\Mail\DispatchedEmail;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class   UnsubscribeMailshot
{
    use WithActionUpdate;

    public function handle(DispatchedEmail $dispatchedEmail): DispatchedEmail
    {
        if (Arr::get($dispatchedEmail->data, 'is_test', false)) {
            return $dispatchedEmail;
        }

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
            'message' => match ($dispatchedEmail->state) {
                DispatchedEmailStateEnum::UNSUBSCRIBED => [
                    'title' => __('You have already unsubscribed from this mailshot'),
                    'description' => __("We're sorry to see you go. If you unsubscribe by mistakes, you can resubscribe 'here'.")
                ],
                default => [
                    'title' => __('You have been unsubscribed from this mailshot'),
                    'description' => "If you unsubscribe by mistakes, you can resubscribe 'here'."
                ]
            }
        ]);
    }
}
