<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 18 Oct 2023 12:19:34 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Actions\SysAdmin\OrganisationUser\Traits\WithOrgAuthLog;
use App\Enums\Elasticsearch\ElasticsearchUserRequestTypeEnum;
use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class LogOrganisationUserLogout
{
    use AsAction;
    use WithOrgAuthLog;

    public function handle(OrganisationUser $organisationUser, string $ip, string $userAgent, Carbon $datetime): void
    {
        $this->logOrgAuthAction(ElasticsearchUserRequestTypeEnum::LOGOUT, $datetime, $ip, $userAgent, $organisationUser);
    }


}
