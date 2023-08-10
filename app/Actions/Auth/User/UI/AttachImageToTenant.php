<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Auth\User\UI;

use App\Actions\Traits\WithActionUpdate;
use App\Models\Tenancy\Tenant;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AttachImageToTenant
{
    use AsAction;
    use WithActionUpdate;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(Tenant $tenant, string $collection, string $imagePath, string $originalFilename, string $extension = null): Media
    {
        $checksum = md5_file($imagePath);
        /** @var Media $media */
        $media = $tenant->media()->where('collection_name', $collection)->where('checksum', $checksum)->first();
        if (!$media) {
            $media = $tenant->addMedia($imagePath)
                ->preservingOriginal()
                ->withProperties(['checksum' => $checksum])
                ->usingName($originalFilename)
                ->usingFileName($checksum.".".$extension ?? pathinfo($imagePath, PATHINFO_EXTENSION))
                ->toMediaCollection($collection);
        }

        return $media;
    }
}
