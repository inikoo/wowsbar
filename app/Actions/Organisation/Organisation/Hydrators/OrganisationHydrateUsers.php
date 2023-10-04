<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Models\Auth\Guest;
use App\Models\Auth\OrganisationUser;
use App\Models\HumanResources\Employee;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateUsers
{
    use AsAction;

    public function handle(): void
    {
        $numberUsers             = OrganisationUser::count();
        $numberActiveUsers       = OrganisationUser::where('status', true)->count();
        $numberUserTypeEmployees = OrganisationUser::where('parent_type', class_basename(Employee::class))->count();
        $numberUserTypeGuest     = OrganisationUser::where('parent_type', class_basename(Guest::class))->count();

        $stats = [
            'number_organisation_users'                 => $numberUsers,
            'number_organisation_users_status_active'   => $numberActiveUsers,
            'number_organisation_users_status_inactive' => $numberUsers - $numberActiveUsers,
            'number_organisation_users_type_employee'   => $numberUserTypeEmployees,
            'number_organisation_users_type_guest'      => $numberUserTypeGuest
        ];


        organisation()->stats->update($stats);
    }
}
