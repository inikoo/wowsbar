<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 10:24:04 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\CRM\Customer;

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
