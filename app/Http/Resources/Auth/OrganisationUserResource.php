<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 04 Oct 2023 10:56:00 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Auth;

use App\Models\Auth\OrganisationUser;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use JsonSerializable;

class OrganisationUserResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var OrganisationUser $organisationUser */
        $organisationUser = $this;


        return [
            'id'           => $organisationUser->id,
            'slug'         => $organisationUser->slug,
            'username'     => $organisationUser->username,
            'email'        => $organisationUser->email,
            'avatar'       => $organisationUser->avatarImageSources(48, 48),
            'contact_name' => $organisationUser->contact_name,
            'created_at'   => $organisationUser->created_at,
            'updated_at'   => $organisationUser->updated_at,
            'status'       => $organisationUser->status ? 'Active' : 'Suspended',
            'roles'        => Arr::join($organisationUser->getRoleNames()->toArray(), ', '),
        ];
    }
}
