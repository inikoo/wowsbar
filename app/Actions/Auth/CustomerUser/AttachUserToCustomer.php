<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser;

use App\Actions\Auth\CustomerUser\Hydrators\CustomerUserHydrateUniversalSearch;
use App\Models\Auth\User;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachUserToCustomer
{
    use AsAction;

    public function handle(Customer $customer, User $user)
    {
        $customer->users()->attach($user->id);

        $customerUser=$customer->users()->where('user_id', $user->id)->first();

        CustomerUserHydrateUniversalSearch::dispatch($customerUser->pivot);

    }
}
