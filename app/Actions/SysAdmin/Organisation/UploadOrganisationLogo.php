<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 17 Oct 2023 19:39:52 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation;

use App\Actions\Traits\WithActionUpdate;
use App\Actions\Traits\WIthSaveUploadedImage;
use App\Models\SysAdmin\Organisation;
use Lorisleiva\Actions\Concerns\AsAction;

class UploadOrganisationLogo
{
    use AsAction;
    use WithActionUpdate;
    use WIthSaveUploadedImage;


    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Organisation $organisation, string $imagePath, string $originalFilename, string $extension = null): Organisation
    {
        return $this->saveUploadedImage(
            model: $organisation,
            collection: 'logo',
            field: 'logo_id',
            imagePath: $imagePath,
            originalFilename: $originalFilename,
            extension: $extension,
        );
    }
}
