<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 01 Jun 2023 15:06:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Firebase;

use App\Models\Auth\User;
use App\Models\Tenancy\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsObject;

class StoreUserLogFirebase
{
    use AsObject;
    use AsAction;

    public function handle(User $user, Tenant $tenant, array $route): void
    {
        $database  = app('firebase.database');
        $reference = $database->getReference('tenants/' . $tenant->slug . '/active_users/' . $user->username);

        $reference->set([
            'last_active' => now(),
            'route'       => $route
        ]);

        CheckUserStatusFirebase::dispatch($tenant);
    }
}
