<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 11:52:43 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User;

use App\Actions\Auth\User\Traits\WithAuthLog;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\CustomerUser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogUserLogout
{
    use AsAction;
    use WithAuthLog;

    public function handle(CustomerUser $customerUser, string $ip, string $userAgent, Carbon $datetime): void
    {
        $this->logAuthAction(ElasticsearchUserRequestTypeEnum::LOGOUT, $datetime, $ip, $userAgent, $customerUser);
    }


}
