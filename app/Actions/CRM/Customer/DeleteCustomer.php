<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:32:25 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer;

use App\Actions\Traits\WithActionUpdate;
use App\Models\CRM\Customer;

class DeleteCustomer
{
    use WithActionUpdate;

    public function handle(Customer $customer): Customer
    {
        $customer->delete();

        return $customer;
    }
}
