<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 17 Oct 2023 18:23:18 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\CRM;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Models\Media\Media;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property int $avatar_id
 * @property string $slug
 * @property string $email
 * @property string $contact_name
 * @property boolean $is_root
 * @property mixed $roles
 * @property boolean $status
 */
class OrgCustomerUsersResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        $imageThumbnail = null;
        if ($this->avatar_id) {
            $media          = Media::find($this->avatar_id);
            $imageThumbnail = (new Image())->make($media->getImgProxyFilename())->resize(0, 48);
        }


        return [
            'slug'         => $this->slug,
            'email'        => $this->email,
            'avatar'       => $imageThumbnail ? GetPictureSources::run($imageThumbnail) : null,
            'contact_name' => $this->contact_name,
            'status'       => $this->is_root ? 'Account admin' : ($this->status ? 'Active' : 'Suspended'),
            'roles'        => json_decode($this->roles),
        ];
    }
}
