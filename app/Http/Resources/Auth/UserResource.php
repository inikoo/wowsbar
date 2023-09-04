<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Auth;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

/**
 * @property mixed $avatar_id
 */
class UserResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Auth\User $user */
        $user = $this;
        return [
            'id'                 => $user->id,
            'username'           => $user->username,
            'avatar'             => $user->avatarImageSources(48, 48),
            'email'              => $user->email,
            'contact_name'       => $user->contact_name,
            'status'             => $user->status ? 'Active' : 'Suspended',
            'roles'              => Arr::join($user->getRoleNames()->toArray(), ', '),
            'direct-permissions' => $user->getDirectPermissions(),
            'permissions'        => Arr::join($user->getAllPermissions()->pluck('name')->toArray(), ', '),
            'created_at'         => $user->created_at,
            'updated_at'         => $user->updated_at
        ];
    }
}
