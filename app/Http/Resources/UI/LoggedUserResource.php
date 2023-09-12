<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 21 Mar 2023 21:10:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UI;

use App\Http\Resources\HasSelfCall;
use App\Models\Auth\User;
use App\Models\Organisation\OrganisationUser;
use Illuminate\Http\Resources\Json\JsonResource;

class LoggedUserResource extends JsonResource
{
    use HasSelfCall;


    public function toArray($request): array
    {
        /** @var User|OrganisationUser|\App\Models\CRM\PublicUser $user */
        $user=$this;
        return [
            'username'           => $user->username,
            'avatar_thumbnail'   => ! blank($user->avatar_id) ? $user->avatarImageSources(0, 48) : null
        ];
    }
}
