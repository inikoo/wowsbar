<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation;

use App\Actions\HydrateModel;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomers;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomerUsers;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomerWebsites;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateDivisions;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateEmployees;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateGuests;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateJobPositions;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateShops;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateTaskTypes;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateWebsites;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateWorkplaces;
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
