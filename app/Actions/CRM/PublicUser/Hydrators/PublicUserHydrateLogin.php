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

class PublicUserHydrateLogin
{
    use AsAction;

    public function handle(PublicUser $user, string $ip, Carbon $datetime): void
    {
        $stats = [
            'last_login_at' => $datetime,
            'last_login_ip' => $ip,
            'number_logins' => $user->stats->number_logins + 1
        ];


        $user->stats()->update($stats);
    }


}
