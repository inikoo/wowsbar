<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:36:51 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User\UI;

use App\Actions\Traits\WithActionUpdate;
use App\Actions\Traits\WIthSaveUploadedImage;
use App\Models\Auth\User;
use Lorisleiva\Actions\Concerns\AsAction;

class SetUserAvatarFromImage
{
    use AsAction;
    use WithActionUpdate;
    use WIthSaveUploadedImage;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(User $user, string $imagePath, string $originalFilename, string $extension = null): User
    {
        return $this->saveUploadedImage(
            model: $user,
            collection: 'profile',
            field: 'avatar_id',
            imagePath: $imagePath,
            originalFilename: $originalFilename,
            extension: $extension,
        );
    }
}
