<?php

namespace Database\Seeders;

use App\Actions\Mail\EmailTemplate\AttachImageToEmailTemplate;
use App\Models\Mail\EmailTemplateCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EmailTemplateSeederBKup extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path              = database_path('seeders/datasets/email-templates/emailTemplate.json');
        $emailTemplateJson = json_decode(file_get_contents($path), true);

        foreach (Arr::get($emailTemplateJson, 'categories') as $emailTemplate) {
            $category = EmailTemplateCategory::create([
                'name' => Arr::get($emailTemplate, 'name'),
            ]);

            foreach (Arr::get($emailTemplate, 'templates') as $template) {
                $basePath = 'seeders/datasets/email-templates/' . Str::slug($category->name) . '/' . explode('.', Arr::get($template, 'content'))[0];

                $filePath = database_path($basePath . '/' . Arr::get($template, 'content'));
                $fileExt  = File::extension($filePath);

                if ($fileExt === 'json') {
                    $title = Arr::get($template, 'name');

                    $emailTemplate = $category->templates()->create([
                        'title'       => $title,
                        'parent_type' => 'Organisation',
                        'parent_id'   => 1,
                        'data'        => '{}',
                        'compiled'    => json_decode(file_get_contents($filePath), true),
                    ]);

                    $imagesPath = database_path($basePath . '/images');
                    if (File::exists($imagesPath)) {
                        foreach (File::files($imagesPath) as $image) {
                            AttachImageToEmailTemplate::run($emailTemplate, 'email_templates', $image->getPathname(), $image->getFilename());
                        }

                        $checksum = md5_file($imagesPath . '/' . Arr::get($template, 'image'));
                        /** @var Media $media */
                        $media = $emailTemplate->media()->where('collection_name', 'email_templates')->where('checksum', $checksum)->first();

                        $emailTemplate->update([
                            'screenshot_id' => $media->id
                        ]);
                    }
                }
            }
        }
    }
}
