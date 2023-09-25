<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:27:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UniversalSearch\Customers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $avatar_id
 */
class UserSearchResultResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Auth\User $user */
        $user = $this;
        return [
            'username'           => $user->username,
            'avatar'             => $this->avatar_id,
            'email'              => $user->email,
            'contact_name'       => $user->contact_name,
            'route'              => [
                'name'       => 'customer.sysadmin.users.index',
                'parameters' => $user->username
            ],
            'icon'   => ['fal', 'fa-terminal']
        ];
    }
}
