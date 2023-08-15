<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 21 Mar 2023 21:10:46 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\UI;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\HasSelfCall;
use App\Models\Auth\PublicUser;
use App\Models\Auth\User;
use App\Models\Organisation\OrganisationUser;
use Illuminate\Http\Resources\Json\JsonResource;


class LoggedUserResource extends JsonResource
{
    use HasSelfCall;


    public function toArray($request): array
    {
        /** @var User|OrganisationUser|PublicUser $user */
        $user=$this;

        $avatarThumbnail = (new Image())->make($user->avatar->getLocalImgProxyFilename())->resize(0, 48);

        return [
            'username'    => $user->username,
            'avatar_thumbnail'   => GetPictureSources::run($avatarThumbnail)
        ];
    }
}
