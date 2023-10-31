<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 01 Nov 2023 01:52:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\Hydrators;

use App\Models\Web\Website;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class WebsiteHydrateCustomerUsers
{
    use AsAction;

    public function handle(Website $website): void
    {
        $numberUsers       = DB::table('customer_user')->leftJoin('customers', 'customers.id', 'customer_id')->where('website_id', $website->id)->count();
        $numberActiveUsers = DB::table('customer_user')->leftJoin('customers', 'customers.id', 'customer_id')->where('website_id', $website->id)->where('customer_user.status', true)->count();

        $stats = [
            'number_customer_users'                 => $numberUsers,
            'number_customer_users_status_active'   => $numberActiveUsers,
            'number_customer_users_status_inactive' => $numberUsers - $numberActiveUsers
        ];

        $website->webStats()->update($stats);
    }
}
