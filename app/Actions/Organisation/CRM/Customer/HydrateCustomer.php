<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Customer;

use App\Actions\Organisation\CRM\Customer\Hydrators\CustomerHydrateBanners;
use App\Actions\Organisation\CRM\Customer\Hydrators\CustomerHydrateInvoices;
use App\Actions\Organisation\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\Organisation\CRM\Customer\Hydrators\CustomerHydrateUniversalSearch;
use App\Actions\Organisation\CRM\Customer\Hydrators\CustomerHydrateUsers;
use App\Models\CRM\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Lorisleiva\Actions\Concerns\AsAction;

class HydrateCustomer
{
    use asAction;



    public function handle(Customer $customer): void
    {
        CustomerHydrateInvoices::run($customer);
        CustomerHydrateUniversalSearch::run($customer);
        CustomerHydrateUsers::run($customer);
        CustomerHydrateBanners::run($customer);
        CustomerHydratePortfolioWebsites::run();
    }



    public string $commandSignature = 'hydrate:customer {customers?*}';

    public function asCommand(Command $command): int
    {

        if(!$command->argument('customers')) {
            $customers=Customer::all();
        } else {
            $customers =  Customer::query()
                ->when($command->argument('customers'), function ($query) use ($command) {
                    $query->whereIn('slug', $command->argument('customers'));
                })
                ->cursor();
        }


        $exitCode = 0;

        foreach ($customers as $customer) {

            Config::set('global.customer_id', $customer->id);
            $this->handle($customer);

        }

        return $exitCode;
    }
}
