<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydrateCustomerUsers
{
    use AsAction;

    public function handle(Customer $customer): void
    {
        $numberUsers       = $customer->customerUsers()->count();
        $numberActiveUsers = $customer->customerUsers()->where('status', true)->count();

        $stats = [
            'number_users'                 => $numberUsers,
            'number_users_status_active'   => $numberActiveUsers,
            'number_users_status_inactive' => $numberUsers - $numberActiveUsers
        ];

        $customer->stats()->update($stats);
    }
}
