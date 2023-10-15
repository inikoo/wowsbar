<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteInterestEnum;
use App\Models\CRM\Customer;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydratePortfolioWebsites
{
    use AsAction;

    public function handle(Customer $customer): void
    {

        $stats = [
            'number_portfolio_websites'       => $customer->portfolioWebsites()->count(),
        ];

        foreach (json_decode(file_get_contents(base_path('database/seeders/datasets/divisions.json')), true) as $division) {
            $customer->portfolioWebsites()->each(function ($portfolioWebsite) use (&$counts, $division) {
                $counts = $portfolioWebsite->divisions()->where('slug', $division['slug'])->count();
            });

            $stats['number_portfolio_websites_division_' . $division['slug']] = $counts??0;

            foreach (PortfolioWebsiteInterestEnum::cases() as $case) {
                $customer->portfolioWebsites()->each(function ($portfolioWebsite) use (&$counts, $case, $division) {
                    $counts = $portfolioWebsite->divisions()->where('slug', $division['slug'])->wherePivot('interest', $case)->count();
                });

                $stats['number_portfolio_websites_' . $division['slug'] . '_' . Str::replace('-', '_', $case->snake())] = $counts??0;
            }
        }

        $customer->portfolioStats()->update($stats);
    }

}
