<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Auth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Auth\User $user */
        $user = $this;

        return [
            'id'           => $user->id,
            'slug'         => $user->slug,
            'email'        => $user->email,
            'avatar'       => $user->avatarImageSources(48, 48),
            'contact_name' => $user->contact_name,
            'created_at'   => $user->created_at,
            'updated_at'   => $user->updated_at
        ];
    }
}
