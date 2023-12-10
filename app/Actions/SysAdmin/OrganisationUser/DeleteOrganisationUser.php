<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 04 Dec 2023 16:24:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\OrganisationUser;

use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateUsers;
use App\Models\Auth\OrganisationUser;
use Lorisleiva\Actions\Concerns\AsObject;

class DeleteOrganisationUser
{
    use AsObject;


    public function handle(OrganisationUser $organisationUser): OrganisationUser
    {
        $organisationUser->updateQuietly([
            'username' => $organisationUser->username . '@deleted-' . $organisationUser->id
        ]);
        $organisationUser->delete();
        OrganisationHydrateUsers::dispatch();
        return $organisationUser;
    }


}
