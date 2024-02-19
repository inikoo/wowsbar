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
use Exception;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Str;

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

        $tmpFile = GetImageFromHtml::run(
            $emailTemplate->compiled['html']['html']
        );


        $this->saveUploadedImage(
            model: $emailTemplate,
            collection: 'screenshot',
            field: 'screenshot_id',
            imagePath: stream_get_meta_data($tmpFile)['uri'],
            originalFilename: Str::kebab($emailTemplate->name) . '.jpg'
        );

        fclose($tmpFile);

        return $emailTemplate;
    }

    public string $commandSignature = 'email-template:screenshot {email_template_id?}';


    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function asCommand(Command $command): int
    {
        if ($command->argument('email_template_id')) {
            try {
                $emailTemplate = EmailTemplate::find($command->argument('email_template_id'));
                if ($emailTemplate) {
                    $this->handle($emailTemplate);
                    $command->info('Screenshot saved');

                    return 0;
                }
            } catch (Exception $e) {
                $command->error($e->getMessage());

                return 1;
            }
        } else {
            foreach (EmailTemplate::where('is_seeded', false)->get() as $emailTemplate) {
                $this->handle($emailTemplate);
            }
        }


        return 0;
    }

}
