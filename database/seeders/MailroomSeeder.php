<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 14 Nov 2023 18:44:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\Mail\Mailroom\StoreMailroom;
use App\Enums\Mail\Mailroom\MailroomCodeEnum;
use App\Models\Mail\Mailroom;
use Illuminate\Database\Seeder;

class MailroomSeeder extends Seeder
{
    public function run(): void
    {

        foreach (MailroomCodeEnum::cases() as $case) {

            $mailroom=Mailroom::where('code', $case->value)->first();
            if(!$mailroom) {
                StoreMailroom::run(
                    [
                        'code' => $case->value
                    ]
                );
            }

        }



    }
}
