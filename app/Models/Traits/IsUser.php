<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 12:14:27 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Traits;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Helpers\ImgProxy\Image;
use App\Models\Assets\Language;
use App\Models\Media\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait IsUser
{
    public function avatar(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'avatar_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function avatarImageSources($width = 0, $height = 0)
    {
        if($this->avatar){
            $avatarThumbnail = (new Image())->make($this->avatar->getLocalImgProxyFilename())->resize($width, $height);

            return GetPictureSources::run($avatarThumbnail);
        }
        return null;

    }

}
