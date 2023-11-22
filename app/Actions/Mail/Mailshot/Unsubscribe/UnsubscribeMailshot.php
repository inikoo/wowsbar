<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Unsubscribe;

use App\Actions\Leads\Prospect\UpdateProspect;
use App\Actions\Mail\DispatchedEmail\UpdateDispatchedEmail;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\CRM\Prospect\ProspectStateEnum;
use App\Enums\Mail\DispatchedEmailEventTypeEnum;
use App\Enums\Mail\DispatchedEmailStateEnum;
use App\Models\Mail\DispatchedEmail;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;

class UnsubscribeMailshot
{
    use WithActionUpdate;

    public function handle(DispatchedEmail $dispatchedEmail, ActionRequest $request): DispatchedEmail
    {
        if ($dispatchedEmail->is_test) {
            return $dispatchedEmail;
        }

        $recipient = $dispatchedEmail->mailshotRecipient->recipient;
        if (class_basename($recipient) == 'Prospect') {
            UpdateProspect::run(
                $recipient,
                ['state' => ProspectStateEnum::NOT_INTERESTED]
            );
        }

        UpdateDispatchedEmail::run(
            $dispatchedEmail,
            [
                'state'           => DispatchedEmailStateEnum::UNSUBSCRIBED,
                'date'            => now(),
                'is_unsubscribed' => true,

            ]
        );

        $eventData = [
            'type'            => DispatchedEmailEventTypeEnum::UNSUBSCRIBE,
            'date'            => now(),
            'data'            => [
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent()
            ]
        ];


        $dispatchedEmail->events()->create($eventData);


        return $this->update($dispatchedEmail, ['state' => DispatchedEmailStateEnum::UNSUBSCRIBED]);
    }

    public function asController(DispatchedEmail $dispatchedEmail, ActionRequest $request): DispatchedEmail
    {
        return $this->handle($dispatchedEmail, $request);
    }

    public function htmlResponse(DispatchedEmail $dispatchedEmail): Response
    {
        return Inertia::render('Utils/Unsubscribe', [
            'title'    => __("Unsubscribe"),
            'mailshot' => $dispatchedEmail,
            'message'  => [
                'title'       => __('Unsubscription successful'),
                'description' => __("You have been unsubscribed, sorry for any inconvenience caused."),
                'caution'     => match ($dispatchedEmail->is_test) {
                    true    => __("This is a test mailshot, no action was taken and you can ignore this message."),
                    default => null
                }
            ]
        ]);
    }
}
