<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Customer;

use App\Actions\HydrateModel;
use App\Actions\Organisation\CRM\Customer\Hydrators\CustomerHydrateInvoices;
use App\Actions\Organisation\CRM\Customer\Hydrators\CustomerHydrateUniversalSearch;
use App\Models\CRM\Customer;
use Illuminate\Support\Collection;

class HydrateCustomer extends HydrateModel
{
    public string $commandSignature = 'hydrate:customer {tenants?*} {--i|id=}';


    public function handle(Customer $customer): void
    {
        CustomerHydrateInvoices::run($customer);
        CustomerHydrateUniversalSearch::run($customer);
    }

    protected function getModel(int $id): Customer
    {
        return Customer::find($id);
    }

    protected function getAllModels(): Collection
    {
        return Customer::withTrashed()->get();
    }
}
