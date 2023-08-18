<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 17 Aug 2023 13:56:26 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Auth\User\UI;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Auth\User;
use App\Models\Media\Media;
use Lorisleiva\Actions\Concerns\AsAction;

class SetUserAvatarFromImage
{
    use AsAction;
    use WithActionUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(User $user, string $imagePath, string $originalFilename, string $extension = null): User
    {
        $checksum = md5_file($imagePath);

        if ($user->getMedia('profile', ['checksum' => $checksum])->count() == 0) {
            $user->update(['avatar_id' => null]);

            /** @var Media $media */
            $media = $user->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(
                    [
                        'checksum'  => $checksum,
                        'tenant_id' => app('currentTenant')->id
                    ]
                )
                ->usingName($originalFilename)
                ->usingFileName($checksum.".".$extension ?? pathinfo($imagePath, PATHINFO_EXTENSION))
                ->toMediaCollection('profile');

            $avatarID = $media->id;
            $user->update(['avatar_id' => $avatarID]);
        }


        return $user;
    }
}
