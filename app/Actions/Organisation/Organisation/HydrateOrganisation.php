<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation;

use App\Actions\HydrateModel;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateCustomers;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateEmployees;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateGuests;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateJobPositions;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateShops;
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
        OrganisationHydrateGuests::run();
        OrganisationHydrateEmployees::run();
        OrganisationHydrateJobPositions::run();
        OrganisationHydrateShops::run();
        OrganisationHydrateWebsites::run();
        OrganisationHydrateCustomers::run();
        OrganisationHydrateWorkplaces::run();

    }

    public function asCommand(Command $command): int
    {
        $this->handle();
        return 0;
    }
}
