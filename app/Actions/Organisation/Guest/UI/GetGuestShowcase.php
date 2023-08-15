<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Guest\UI;

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
