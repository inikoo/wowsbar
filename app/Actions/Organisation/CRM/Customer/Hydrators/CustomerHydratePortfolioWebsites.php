<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 15:04:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Customer\Hydrators;

use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydratePortfolioWebsites
{
    use AsAction;

    public function handle(Customer $customer): void
    {

        $stats = [
            'number_portfolio_websites'       => $customer->portfolioWebsites()->count(),
        ];
        $customer->stats()->update($stats);
    }

}
