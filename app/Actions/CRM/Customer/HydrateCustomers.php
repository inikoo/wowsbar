<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\CRM\Customer\Hydrators\CustomerHydrateBanners;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateInvoices;
use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebpages;
use App\Actions\CRM\Customer\Hydrators\CustomerHydratePortfolioWebsites;
use App\Actions\CRM\Customer\Hydrators\CustomerHydrateCustomerUsers;
use App\Actions\HydrateModel;
use App\Models\CRM\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class HydrateCustomers extends HydrateModel
{
    public function handle(Customer $customer): void
    {
        Config::set('global.customer_id', $customer->id);
        //CustomerHydrateInvoices::run($customer);
        CustomerHydrateCustomerUsers::run($customer);
        CustomerHydrateBanners::run($customer);
        CustomerHydratePortfolioWebsites::run($customer);
        CustomerHydratePortfolioWebpages::run($customer);
    }

    public string $commandSignature = 'customer:hydrate {slugs?*}';

    protected function getModel(string $slug): Customer
    {
        return Customer::firstWhere($slug);
    }

    protected function getAllModels(): Collection
    {
        return Customer::get();
    }

}
