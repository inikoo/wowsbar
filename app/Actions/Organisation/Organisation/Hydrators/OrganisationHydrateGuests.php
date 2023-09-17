<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Models\Auth\Guest;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateGuests
{
    use AsAction;

    public function handle(): void
    {
        $numberGuests = Guest::count();

        $numberActiveGuests = Guest::where('status', true)
            ->count();


        $stats = [
            'number_guests'                 => $numberGuests,
            'number_guests_status_active'   => $numberActiveGuests,
            'number_guests_status_inactive' => $numberGuests - $numberActiveGuests,
        ];


        organisation()->stats->update($stats);
    }
}
