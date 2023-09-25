<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Models\CustomerWebsites\CustomerWebsite;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateCustomerWebsites
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {
        $stats = [
            'number_customer_websites' => CustomerWebsite::count()
        ];
        organisation()->crmStats()->update($stats);
    }
}
