<?php

namespace Database\Seeders;

use App\Models\EmailTemplateCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EmailTemplateSeeder extends Seeder
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
                $filePath = database_path('seeders/datasets/email-templates/' . Str::slug($category->name) . '/' . Arr::get($template, 'content'));
                $fileExt  = File::extension($filePath);

                if ($fileExt === 'json') {
                    $title = Arr::get($template, 'name');

                    $category->templates()->create([
                        'title'       => $title,
                        'parent_type' => 'Organisation',
                        'parent_id'   => 1,
                        'data'        => '{}',
                        'compiled'    => json_decode(file_get_contents($filePath), true),
                    ]);

                }
            }
        }
    }
}
