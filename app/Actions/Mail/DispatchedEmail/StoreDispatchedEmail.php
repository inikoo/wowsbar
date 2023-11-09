<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 08:23:57 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\DispatchedEmail;

use App\Models\Mail\DispatchedEmail;
use App\Models\Mail\Email;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreDispatchedEmail
{
    use AsAction;

    public function handle(Email $email): DispatchedEmail
    {
        /** @var DispatchedEmail $dispatchedEmail */
        $dispatchedEmail = DispatchedEmail::create(
            [
                'email_id' => $email->id,
                'ulid'     => Str::ulid(),
                'date'     => now()
            ]
        );
        return $dispatchedEmail;
    }



}
