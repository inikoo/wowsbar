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

class OrganisationUserHydrateFailLogin
{
    use AsAction;

    public function handle(OrganisationUser $organisationUser, string $ip, Carbon $datetime): void
    {

        $stats = [
            'number_failed_logins'    => $organisationUser->stats->number_failed_logins+1,
            'last_failed_login_ip'    => $ip,
            'last_failed_login_at'    => $datetime
        ];

        $organisationUser->stats()->update($stats);
    }


}