<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 14 Oct 2023 13:42:01 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Actions\Traits\WithActionUpdate;
use App\Actions\Traits\WIthSaveUploadedImage;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadWebsiteLogo
{
    use AsAction;
    use WithActionUpdate;
    use WIthSaveUploadedImage;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Website $website, string $imagePath, string $originalFilename, string $extension = null): Website
    {
        return $this->saveUploadedImage(
            model: $website,
            collection: 'logo',
            field: 'logo_id',
            imagePath: $imagePath,
            originalFilename: $originalFilename,
            extension: $extension,
        );
    }
}
