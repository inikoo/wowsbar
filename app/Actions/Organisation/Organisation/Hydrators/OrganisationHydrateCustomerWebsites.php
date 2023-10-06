<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Models\Organisation\Division;
use App\Models\Portfolios\CustomerWebsite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
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

        foreach (json_decode(file_get_contents(base_path('database/seeders/datasets/divisions.json')), true) as $division) {
            $divisionId = Cache::get($division['slug']);

            if(! $divisionId) {
                $divisionId = Division::firstWhere('slug', $division['slug'])->id;
                Cache::put($division['slug'], $divisionId);
            }

            $customerWebsiteCount = CustomerWebsite::join('division_portfolio_websites', 'portfolio_websites.id', 'division_portfolio_websites.portfolio_website_id')
                ->where('division_portfolio_websites.division_id', $divisionId)->count();

            $stats['number_customer_websites_' . Str::replace('-', '_', $division['slug'])] = $customerWebsiteCount;
        }

        organisation()->crmStats()->update($stats);
    }
}
