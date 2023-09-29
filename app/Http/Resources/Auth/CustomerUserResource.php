<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:31:59 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Auth;

use App\Models\Auth\CustomerUser;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class CustomerUserResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var CustomerUser $customerUser */
        $customerUser = $this;


        return [
            'id'                 => $customerUser->id,
            'slug'               => $customerUser->slug,
            'email'              => $customerUser->user->email,
            'avatar'             => $customerUser->user->avatarImageSources(48, 48),
            'contact_name'       => $customerUser->user->contact_name,
            'status'             => $customerUser->status ? 'Active' : 'Suspended',
            'roles'              => Arr::join($customerUser->getRoleNames()->toArray(), ', '),
            'direct-permissions' => $customerUser->getDirectPermissions(),
            'permissions'        => Arr::join($customerUser->getAllPermissions()->pluck('name')->toArray(), ', '),

        ];
    }
}
