<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 09 Jun 2024 13:34:03 Central European Summer Time, Plane Abu Dhabi - Kuala Lumpur
 * Copyright (c) 2024, Raul A Perusquia Flores
 */

namespace App\Actions\Media\Media;

use App\Actions\Helpers\Media\StoreMediaFromFile;
use App\Models\CRM\Customer;
use App\Models\HumanResources\Employee;
use App\Models\SysAdmin\Organisation;
use App\Models\Web\WebBlockType;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveModelImage
{
    use AsAction;

    public function handle(
        Employee|Customer|Organisation|WebBlockType $model,
        array $imageData,
        string $scope = 'image'
    ): Employee|Customer|Organisation|WebBlockType {
        $oldImage = $model->image;

        $checksum = md5_file($imageData['path']);

        if ($oldImage && $oldImage->checksum == $checksum) {
            return $model;
        }

        data_set($imageData, 'checksum', $checksum);
        $media = StoreMediaFromFile::run($model, $imageData, 'image');

        if ($oldImage && $oldImage->id == $media->id) {
            return $model;
        }

        if ($media) {
            $model->updateQuietly(['image_id' => $media->id]);

            $model->images()->sync(
                [
                    $media->id => [
                        'scope'           => $scope
                    ]
                ]
            );
            if ($oldImage) {
                $oldImage->delete();
            }
        }

        return $model;
    }

}
