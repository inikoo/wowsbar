<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 26 Dec 2023 21:53:25 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Models\CRM\Customer;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydratePortfolioWebpages
{
    use AsAction;

    private Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getJobMiddleware(): array
    {
        return [(new WithoutOverlapping($this->customer->id))->dontRelease()];
    }

    public function handle(Customer $customer): void
    {
        $stats = [
            'number_portfolio_webpages' => $customer->customerWebpages()->count(),
        ];




        $customer->portfolioStats()->update($stats);
    }

}
