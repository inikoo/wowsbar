<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 13:51:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Guest\Hydrators;

use App\Models\Organisation\Guest;
use Lorisleiva\Actions\Concerns\AsAction;

class GuestHydrateUniversalSearch
{
    use AsAction;

    public function handle(Guest $guest): void
    {

        $guest->universalSearch()->updateOrCreate(
            [],
            [
                'section'     => 'sysadmin',
                'title'       => trim($guest->slug.' '.$guest->contact_name),
                'description' => $guest->contact_name.' '.$guest->email.' '.$guest->phone.' '.$guest->company_name
            ]
        );
    }


}