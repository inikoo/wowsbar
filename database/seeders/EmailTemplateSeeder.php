<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Dec 2023 02:34:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace Database\Seeders;

use App\Actions\Mail\EmailTemplate\AttachImageToEmailTemplate;
use App\Actions\Mail\EmailTemplate\SetEmailTemplateScreenshot;
use App\Actions\Mail\EmailTemplate\StoreEmailTemplate;
use App\Models\Mail\EmailTemplate;
use App\Models\Mail\EmailTemplateCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedEmailTemplateCategories();
        $templates = json_decode(file_get_contents(database_path('seeders/datasets/email-templates/email-templates.json')), true);
        foreach ($templates as $template) {
            $basePath = 'seeders/datasets/email-templates/'.explode('.', Arr::get($template, 'content'))[0];

            $filePath = database_path($basePath.'/'.Arr::get($template, 'content'));


            if ($emailTemplate = EmailTemplate::where('name', Arr::get($template, 'name'))->where('is_seeded', true)->first()) {
                $emailTemplate->update([
                    'name' => Arr::get($template, 'name'),
                ]);
            } else {
                $emailTemplate = StoreEmailTemplate::make()->action(
                    organisation(),
                    [
                        'name'      => Arr::get($template, 'name'),
                        'type'      => Arr::get($template, 'type'),
                        'compiled'  => json_decode(file_get_contents($filePath), true),
                        'is_seeded' => true
                    ]
                );
            }

            $imagesPath = database_path($basePath . '/images');

            if (File::exists($imagesPath)) {

                foreach (File::files($imagesPath) as $image) {
                    AttachImageToEmailTemplate::run(
                        $emailTemplate,
                        'content',
                        $image->getPathname(),
                        $image->getFilename()
                    );
                }

                SetEmailTemplateScreenshot::run(
                    $emailTemplate,
                    database_path($basePath) . '/' . Arr::get($template, 'image'),
                    Arr::get($template, 'image')
                );
            }
        }
    }

    private function seedEmailTemplateCategories(): void
    {
        $categoriesData = json_decode(file_get_contents(database_path('seeders/datasets/email-template-categories.json')), true);

        foreach ($categoriesData as $categoriesDatum) {
            if ($emailTemplateCategory = EmailTemplateCategory::where('name', Arr::get($categoriesDatum, 'name'))->where('is_seeded', true)->first()) {
                $emailTemplateCategory->update([
                    'name' => Arr::get($categoriesDatum, 'name'),
                    'icon' => Arr::get($categoriesDatum, 'icon')
                ]);
            } else {
                EmailTemplateCategory::create([
                    'name'      => Arr::get($categoriesDatum, 'name'),
                    'icon'      => Arr::get($categoriesDatum, 'icon'),
                    'is_seeded' => true
                ]);
            }
        }
    }

}
