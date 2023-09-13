<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\PublicUser\Hydrators;

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
