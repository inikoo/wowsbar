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

class CustomerUserSearchResultResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var \App\Models\Auth\CustomerUser $customerUser */
        $customerUser = $this;

        return [
            'slug'         => $customerUser->slug,
            'avatar'       => $customerUser->user->avatarImageSources(0, 180),
            'email'        => $customerUser->user->email,
            'contact_name' => $customerUser->user->contact_name,
            'route'        => [
                'name'       => 'customer.sysadmin.users.show',
                'parameters' => $customerUser->slug
            ],
            'icon'         => ['fal', 'fa-terminal'],
            'roles'        => __('xxxxxxxxxx')
        ];
    }
}
