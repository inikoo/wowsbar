<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Firebase;

use App\Models\Auth\OrganisationUser;
use App\Models\Auth\User;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsObject;

class StoreUserLogFirebase
{
    use AsObject;
    use AsAction;

    public function handle(User|OrganisationUser $user, string $parentType, ?string $parentSlug, bool $loggedIn, ?array $route): void
    {
        $database = app('firebase.database');
        $path     = match ($parentType) {
            'Tenant' => 'tenants',
            default  => 'org',
        };

        if ($parentSlug) {
            $path .= "/$parentSlug";
        }
        $path .= '/active_users/'.$user->username;

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
