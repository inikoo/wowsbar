<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation;

use App\Actions\HydrateModel;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateCustomers;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateCustomerUsers;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateDivisions;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateEmployees;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateGuests;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateJobPositions;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateShops;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateTaskTypes;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateWebsites;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateWorkplaces;
use App\Actions\Traits\WithNormalise;
use Illuminate\Console\Command;

class HydrateOrganisation extends HydrateModel
{
    use WithNormalise;

    public string $commandSignature = 'hydrate:organisation';


    public function handle(): void
    {
        OrganisationHydrateDivisions::run();
        OrganisationHydrateTaskTypes::run();
        OrganisationHydrateJobPositions::run();
        OrganisationHydrateGuests::run();
        OrganisationHydrateEmployees::run();
        OrganisationHydrateShops::run();
        OrganisationHydrateWebsites::run();
        OrganisationHydrateCustomers::run();
        OrganisationHydrateWorkplaces::run();
        OrganisationHydrateCustomerWebsites::run();
        OrganisationHydrateCustomerUsers::run();

    }

    public function asCommand(Command $command): int
    {
        $this->handle();
        return 0;
    }
}
