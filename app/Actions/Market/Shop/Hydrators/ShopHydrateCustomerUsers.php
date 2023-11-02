<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 01 Nov 2023 00:59:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Market\Shop\Hydrators;

use App\Models\Market\Shop;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ShopHydrateCustomerUsers
{
    use AsAction;

    public function handle(Shop $shop): void
    {
        $numberUsers       = DB::table('customer_user')->leftJoin('customers', 'customers.id', 'customer_id')->where('shop_id', $shop->id)->count();
        $numberActiveUsers = DB::table('customer_user')->leftJoin('customers', 'customers.id', 'customer_id')->where('shop_id', $shop->id)->where('customer_user.status', true)->count();

        $stats = [
            'number_customer_users'                 => $numberUsers,
            'number_customer_users_status_active'   => $numberActiveUsers,
            'number_customer_users_status_inactive' => $numberUsers - $numberActiveUsers
        ];

        $shop->crmStats()->update($stats);
    }
}
