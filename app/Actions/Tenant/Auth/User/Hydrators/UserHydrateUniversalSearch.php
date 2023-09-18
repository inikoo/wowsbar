<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\User\Hydrators;

use App\Models\Auth\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UserHydrateUniversalSearch
{
    use AsAction;

    public function handle(User $user): void
    {
        $user->universalSearch()->create(
            [
                'website_id'  => $user->website_id,
                'shop_id'     => $user->website->shop_id,
                'customer_id' => $user->customer_id,
                'section'     => 'sysadmin',
                'title'       => $user->username,
                'description' => trim($user->email.' '.$user->contact_name)
            ]
        );
    }
}
