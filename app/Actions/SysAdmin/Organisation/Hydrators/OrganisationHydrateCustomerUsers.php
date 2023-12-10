<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 01 Nov 2023 00:55:41 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Models\Auth\CustomerUser;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateCustomerUsers
{
    use AsAction;

    public function handle(): void
    {
        $numberUsers       = CustomerUser::count();
        $numberActiveUsers = CustomerUser::where('status', true)->count();

        $stats = [
            'number_customer_users'                 => $numberUsers,
            'number_customer_users_status_active'   => $numberActiveUsers,
            'number_customer_users_status_inactive' => $numberUsers - $numberActiveUsers
        ];

        organisation()->crmStats()->update($stats);
    }
}
