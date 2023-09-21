<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:36:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\Hydrators;

use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationUserHydrateLogin
{
    use AsAction;

    public function handle(OrganisationUser $organisationUser, string $ip, Carbon $datetime): void
    {
        $stats = [
            'last_login_at' => $datetime,
            'last_login_ip' => $ip,
            'number_logins' => $organisationUser->stats->number_logins + 1
        ];
        $organisationUser->stats()->update($stats);
    }


}
