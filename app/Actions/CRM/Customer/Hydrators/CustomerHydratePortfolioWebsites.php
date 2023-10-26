<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Enums\Divisions\DivisionEnum;
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
            'number_portfolio_websites' => $customer->portfolioWebsites()->count(),
        ];


        foreach (DivisionEnum::cases() as $division) {
            $customer->portfolioWebsites()->each(function ($portfolioWebsite) use (&$counts, $division) {
                $counts = $portfolioWebsite->divisions()->where('slug', $division->snake())->count();
            });

            $stats['number_portfolio_websites_division_'.$division->snake()] = $counts ?? 0;

            foreach (PortfolioWebsiteInterestEnum::cases() as $case) {
                $customer->portfolioWebsites()->each(function ($portfolioWebsite) use (&$counts, $case, $division) {
                    $counts = $portfolioWebsite->divisions()->where('slug', $division->snake())->wherePivot('interest', $case)->count();
                });

                $stats['number_portfolio_websites_'.$division->snake().'_'.Str::replace('-', '_', $case->snake())] = $counts ?? 0;
            }
        }


        $customer->portfolioStats()->update($stats);
    }

}
