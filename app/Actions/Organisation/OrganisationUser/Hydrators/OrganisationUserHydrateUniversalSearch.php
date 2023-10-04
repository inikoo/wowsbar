<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 13:51:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\Hydrators;

use App\Models\Auth\Guest;
use App\Models\Auth\OrganisationUser;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationUserHydrateUniversalSearch
{
    use AsAction;

    public function handle(OrganisationUser $user): void
    {
        $user->universalSearch()->updateOrCreate(
            [],
            [
                'in_organisation' => true,
                'section'         => 'sysadmin',
                'title'           => trim($user->username.' '.$user->contact_name),
                'description'     => $user->contact_name.' '.$user->email
            ]
        );
    }
}
