<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:38 Malaysia Time, Kuala Lumpur, Malaysia
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
                'section' => 'User',
                'route'   => json_encode([
                    'name'      => 'sysadmin.users.index',
                    'arguments' => [
                        $user->username
                    ]
                ]),
                'icon'           => 'fa-user',
                'primary_term'   => $user->username,
                'secondary_term' => trim($user->email.' '.$user->contact_name)
            ]
        );
    }

}
