<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:57:40 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Auth\Guest\UI;

use App\Models\Organisation\Guest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetGuestShowcase
{
    use AsObject;

    public function handle(Guest $guest): array
    {
        return [
            [

            ]
        ];
    }
}
