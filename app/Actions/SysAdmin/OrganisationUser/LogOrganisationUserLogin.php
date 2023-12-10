<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 12:26:29 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Actions\SysAdmin\OrganisationUser\Traits\WithOrgAuthLog;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\OrganisationUser;
use App\Models\SysAdmin\Organisation;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogOrganisationUserLogin
{
    use AsAction;
    use WithOrgAuthLog;


    public function handle(Organisation $organisation, OrganisationUser $organisationUser, string $ip, string $userAgent, Carbon $datetime): void
    {
        $this->logOrgAuthAction(ElasticsearchUserRequestTypeEnum::LOGIN, $datetime, $ip, $userAgent, $organisationUser);

        $stats = [
            'last_login_at' => $datetime,
            'last_login_ip' => $ip,
            'number_logins' => $organisationUser->stats->number_logins + 1
        ];

        $organisationUser->stats()->update($stats);

        $organisation->stats()->update(
            [
                'last_login_at' => $datetime,
                'number_logins' => $organisation->stats->number_logins + 1,
            ]
        );
    }


}
