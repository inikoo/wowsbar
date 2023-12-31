<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User\Hydrators;

use App\Models\Auth\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UserHydrateUniversalSearch
{
    use AsAction;

    public function handle(User $user): void
    {
        $user->universalSearch()->create(
            [
                'in_organisation' => true,
                'website_id'      => $user->website_id,
                'shop_id'         => $user->website->shop_id,
                'section'         => 'sysadmin',
                'title'           => $user->email,
                'description'     => $user->contact_name
            ]
        );
    }
}
