<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\CustomerUser\Hydrators;

use App\Models\Auth\CustomerUser;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerUserHydrateUniversalSearch
{
    use AsAction;

    public function handle(CustomerUser $customerUser): void
    {
        $customerUser->universalSearch()->updateOrCreate(
            [],
            [
                'in_organisation' => true,
                'website_id'      => $customerUser->user->website_id,
                'shop_id'         => $customerUser->user->website->shop_id,
                'section'         => 'sysadmin',
                'customer_id'     => $customerUser->customer_id,
                'title'           => join(' ', [$customerUser->slug, $customerUser->user->email, $customerUser->user->contact_name]),
                'description'     => $customerUser->user->about
            ]
        );
    }
}
