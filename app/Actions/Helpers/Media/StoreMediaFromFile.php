<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 09 Jun 2024 13:34:03 Central European Summer Time, Plane Abu Dhabi - Kuala Lumpur
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Media;

use App\Actions\Media\Media\UpdateIsAnimatedMedia;
use App\Models\Media\Media;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreMediaFromFile
{
    use AsAction;

    public function handle($model, $imageData, $collection, $type = 'image'): Media
    {
        $extension = Arr::get($imageData, 'extension');
        if (!$extension) {
            $extension = pathinfo($imageData['path'], PATHINFO_EXTENSION);
        }



        $media = $model->addMedia($imageData['path'])
            ->preservingOriginal()
            ->withProperties(
                array_merge(
                    [
                        'checksum' => $imageData['checksum'],
                    ],
                )
            )
            ->usingName($imageData['originalName'])
            ->usingFileName($imageData['checksum'].'.'.$extension)
            ->toMediaCollection($collection);

        UpdateIsAnimatedMedia::run($media, $imageData['path']);

        return $media;
    }
}
