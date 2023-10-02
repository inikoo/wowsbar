<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 02 Oct 2023 14:14:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Firebase;

use App\Models\Auth\OrganisationUser;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsObject;

class StoreLiveOrganisationUserToFirebase
{
    use AsObject;
    use AsAction;

    public function handle(OrganisationUser $organisationUser, bool $loggedIn, ?array $route): void
    {
        $database = app('firebase.database');
        $path     = 'org/active_users/'.$organisationUser->username;

        $reference = $database->getReference($path);

        $data=[
            'loggedIn'    => $loggedIn,
            'last_active' => now(),
        ];
        if($route) {
            $data['route']=$route;
        }


        $reference->set($data);
    }
}
