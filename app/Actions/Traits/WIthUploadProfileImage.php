<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 17 Oct 2023 15:23:45 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\Auth\OrganisationUser;
use App\Models\Auth\User;
use App\Models\Media\Media;

trait WIthUploadProfileImage
{
    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     */
    public function uploadProfileImage(OrganisationUser|User $userable, string $imagePath, string $originalFilename, string $extension = null): OrganisationUser|User
    {

        $checksum = md5_file($imagePath);


        if ($userable->getMedia('profile', ['checksum' => $checksum])->count() == 0) {
            $userable->update(['avatar_id' => null]);

            /** @var Media $media */
            $media = $userable->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(
                    [
                        'checksum'    => $checksum,
                    ]
                )
                ->usingName($originalFilename)
                ->usingFileName($checksum.".".$extension ?? pathinfo($imagePath, PATHINFO_EXTENSION))
                ->toMediaCollection('profile');
            $avatarID = $media->id;
            $userable->update(['avatar_id' => $avatarID]);
        }


        return $userable;
    }
}
