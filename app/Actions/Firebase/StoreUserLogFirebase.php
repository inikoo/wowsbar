<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Firebase;

use App\Models\Auth\User;
use App\Models\Organisation\OrganisationUser;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsObject;

class StoreUserLogFirebase
{
    use AsObject;
    use AsAction;

    public function handle(User|OrganisationUser $user, string $parent_type, ?string $slug, array $route): void
    {
        $database = app('firebase.database');
        $path     = match ($parent_type) {
            'Tenant' => 'tenants',
            default => 'organisations',
        };

        if ($slug) {
            $path .= "/$slug";
        }
        $path .= '/active_users/'.$user->username;

        $reference = $database->getReference($path);

        $reference->set([
            'last_active' => now(),
            'route'       => $route
        ]);
        //        CheckUserStatusFirebase::dispatch($tenant);
    }
}
