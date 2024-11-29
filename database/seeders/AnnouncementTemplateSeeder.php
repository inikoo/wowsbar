<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Dec 2023 02:34:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\Traits\WIthSaveUploadedImage;
use App\Models\AnnouncementTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AnnouncementTemplateSeeder extends Seeder
{
    use WIthSaveUploadedImage;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     */
    public function run(): void
    {
        $screenshotPath = database_path('seeders/datasets/announcement/screenshot');
        $files          = File::files($screenshotPath);

        foreach ($files as $file) {
            $code = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            $announcementTemplate = AnnouncementTemplate::updateOrCreate([
                'code' => $code,
            ], [
                'code' => $code
            ]);

            $this->saveUploadedImage(
                model: $announcementTemplate,
                collection: 'screenshot',
                field: 'screenshot_id',
                imagePath: $file->getPathname(),
                originalFilename: $file->getFilename()
            );

            echo $code . " seeded \n";
        }
    }
}
