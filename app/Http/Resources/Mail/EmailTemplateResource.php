<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 20:01:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Mail;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Http\Resources\HasSelfCall;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResource extends JsonResource
{
    use HasSelfCall;

    public function toArray($request): array
    {
        /** @var \App\Models\Mail\EmailTemplate $emailTemplate */
        $emailTemplate = $this;

        $image          = null;
        $imageThumbnail = null;
        if ($emailTemplate->screenshot) {
            $image          = (new Image())->make($emailTemplate->screenshot->getImgProxyFilename());
            $imageThumbnail = (new Image())->make($emailTemplate->screenshot->getImgProxyFilename())->resize(0, 200);
        }

        return [
            'id'              => $emailTemplate->id,
            'slug'            => $emailTemplate->slug,
            'title'           => $emailTemplate->name,
            'image'           => $image ? GetPictureSources::run($image) : null,
            'image_thumbnail' => $imageThumbnail ? GetPictureSources::run($imageThumbnail) : null,

        ];
    }
}
