<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 19 Sep 2023 16:17:52 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UI;

use App\Http\Resources\HasSelfCall;
use App\Models\Auth\OrganisationUser;
use Illuminate\Http\Resources\Json\JsonResource;

class LoggedOrganisationUserResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var OrganisationUser $user */
        $user=$this;
        return [
            'username'           => $user->username,
            'avatar_thumbnail'   => !blank($user->avatar_id) ? $user->avatarImageSources(0, 48) : null,
        ];
    }
}
