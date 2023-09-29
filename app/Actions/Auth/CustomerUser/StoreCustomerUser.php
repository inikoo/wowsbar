<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser;

use App\Actions\Auth\CustomerUser\Hydrators\CustomerUserHydrateUniversalSearch;
use App\Models\Auth\CustomerUser;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreCustomerUser
{
    use AsAction;

    public function handle(Customer $customer, User $user, $modelData): CustomerUser
    {
        /** @var CustomerUser $customerUser */
        $customerUser=$customer->customerUsers()->createOrFirst(
            [
                'user_id'    => $user->id,
                'customer_id'=> $customer->id
            ],
            $modelData
        );


        if($customerUser->is_root) {
            $superAdminRole = Role::where('guard_name', 'customer')->where('name', 'super-admin')->firstOrFail();
            $customerUser->assignRole($superAdminRole);
        }


        CustomerUserHydrateUniversalSearch::dispatch($customerUser);
        return $customerUser;

    }
}
