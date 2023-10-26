<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithDivision;
use App\Actions\Traits\WithEnumStats;
use App\Enums\Divisions\DivisionEnum;
use App\Models\Portfolios\CustomerWebsite;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateCustomerWebsites
{
    use AsAction;
    use WithEnumStats;
    use WithDivision;

    public function handle(): void
    {
        $stats = [
            'number_customer_websites' => CustomerWebsite::count()
        ];

        foreach (DivisionEnum::cases() as $division) {
            $divisionId           = $this->getCachedDivisionId($division->snake());
            $customerWebsiteCount = CustomerWebsite::join('division_portfolio_websites', 'portfolio_websites.id', 'division_portfolio_websites.portfolio_website_id')
                ->where('division_portfolio_websites.division_id', $divisionId)->count();

            $stats['number_customer_websites_'.$division->snake()] = $customerWebsiteCount;
        }

        organisation()->crmStats()->update($stats);
    }
}
