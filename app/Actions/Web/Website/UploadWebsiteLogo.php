<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 13:42:01 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Media\Media;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadWebsiteLogo
{
    use AsAction;
    use WithActionUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Website $website, string $imagePath, string $originalFilename, string $extension = null): Website
    {
        $checksum = md5_file($imagePath);

        if ($website->getMedia('logo', ['checksum' => $checksum])->count() == 0) {
            $website->update(['logo_id' => null]);

            /** @var Media $media */
            $media = $website->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(
                    [
                        'checksum'    => $checksum,
                    ]
                )
                ->usingName($originalFilename)
                ->usingFileName($checksum.".".$extension ?? pathinfo($imagePath, PATHINFO_EXTENSION))
                ->toMediaCollection('logo');

            $avatarID = $media->id;
            $website->update(['logo_id' => $avatarID]);
        }


        return $website;
    }
}
