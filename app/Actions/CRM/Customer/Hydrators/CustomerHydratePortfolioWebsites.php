<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Enums\Helpers\Interest\InterestEnum;
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
            $stats['number_portfolio_websites_division_' . $division['slug']] = $customer->portfolioWebsites()->each(function ($portfolioWebsite) {
                return $portfolioWebsite->divisions()->count();
            });

            foreach (InterestEnum::cases() as $case) {
                $stats['number_portfolio_websites_' . $division['slug'] . '_' . Str::replace('-', '_', $case->snake())] = $customer->portfolioWebsites()->each(function ($portfolioWebsite) use ($case) {
                    return $portfolioWebsite->divisions()->wherePivot('interest', $case)->count();
                });
            }
        }

        $customer->portfolioStats()->update($stats);
    }

}
