<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
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
