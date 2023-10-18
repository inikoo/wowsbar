<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 12:04:54 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Auth\User\Traits\WithAuthLog;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\CustomerUser;
use App\Models\Web\Website;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogUserLogin
{
    use AsAction;
    use WithAuthLog;


    public function handle(Website $website, CustomerUser $customerUser, string $ip, string $userAgent, Carbon $datetime): void
    {
        $this->logAuthAction(ElasticsearchUserRequestTypeEnum::LOGIN, $datetime, $ip, $userAgent, $customerUser);

        $stats = [
            'last_login_at' => $datetime,
            'last_login_ip' => $ip,
            'number_logins' => $customerUser->user->stats->number_logins + 1
        ];

        $customerUser->user->stats()->update($stats);

        $website->webStats()->update(
            [
                'last_login_at' => $datetime,
                'number_logins' => $website->webStats->number_logins + 1,
            ]
        );
    }


}
