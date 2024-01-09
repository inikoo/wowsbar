<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 10 Nov 2023 14:41:00 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\Mailshot\Unsubscribe;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\Mail\DispatchedEmailResource;
use App\Models\Mail\DispatchedEmail;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class ShowUnsubscribeMailshot
{
    use WithActionUpdate;

    public function handle(DispatchedEmail $dispatchedEmail): DispatchedEmail
    {
        return $dispatchedEmail;
    }

    public function asController(DispatchedEmail $dispatchedEmail): DispatchedEmail
    {
        return $this->handle($dispatchedEmail);
    }

    public function htmlResponse(DispatchedEmail $dispatchedEmail): Response
    {
        $title      =null;
        $description=null;
        if($dispatchedEmail->mailshot) {
            $title       = Arr::get($dispatchedEmail->mailshot->parent->settings, 'mailshot.unsubscribe.title');
            $description = Arr::get($dispatchedEmail->mailshot->parent->settings, 'mailshot.unsubscribe.description');
        }



        return Inertia::render('Utils/Unsubscribe', [
            'title'           => __("Unsubscribe"),
            'dispatchedEmail' => DispatchedEmailResource::make($dispatchedEmail)->getArray(),
            'message'         => [
                'title'       => __($title ?? "Unsubscription successful"),
                'description' => __($description ?? "You have been unsubscribed, sorry for any inconvenience caused."),
                'caution'     => match ($dispatchedEmail->is_test) {
                    true    => __("This is a test mailshot, no action was taken and you can ignore this message."),
                    default => null
                }
            ]
        ]);
    }
}
