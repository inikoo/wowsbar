<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Dec 2023 04:20:13 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Mail\EmailTemplate;

use App\Actions\Helpers\Html\GetImageFromHtml;
use App\Actions\Traits\WithActionUpdate;
use App\Actions\Traits\WIthSaveUploadedImage;
use App\Models\Mail\EmailTemplate;
use Illuminate\Support\Facades\File;
use Lorisleiva\Actions\Concerns\AsAction;

class SetEmailTemplateScreenshot
{
    use AsAction;
    use WithActionUpdate;
    use WIthSaveUploadedImage;

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(EmailTemplate $emailTemplate): EmailTemplate
    {
        $imagesPath = GetImageFromHtml::run(
            $emailTemplate->compiled['html']['html'],
            $emailTemplate->slug
        );

        if (File::exists($imagesPath['path'])) {
            foreach (File::files($imagesPath['path']) as $image) {
                return $this->saveUploadedImage(
                    model: $emailTemplate,
                    collection: 'screenshot',
                    field: 'screenshot_id',
                    imagePath: $image->getPathname(),
                    originalFilename: $image->getFilename()
                );
            }
        }

        return $emailTemplate;
    }
}
