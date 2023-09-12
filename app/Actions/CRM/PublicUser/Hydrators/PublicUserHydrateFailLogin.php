<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 24 Apr 2023 20:23:18 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\PublicUser\Hydrators;

use App\Models\CRM\PublicUser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class PublicUserHydrateFailLogin
{
    use AsAction;

    public function handle(PublicUser $user, string $ip, Carbon $datetime): void
    {

        $stats = [
            'number_failed_logins'    => $user->stats->number_failed_logins+1,
            'last_failed_login_ip'    => $ip,
            'last_failed_login_at'    => $datetime
        ];

        $user->stats()->update($stats);
    }


}
