<?php

namespace Database\Seeders;

use App\Models\Mail\EmailTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = resource_path('views/mailshots/layouts/christmas');
        $files = File::files($path);

        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $fileExt  = $file->getExtension();

            if ($fileExt === 'json') {
                $title = str_replace('.json', '', $fileName);

                EmailTemplate::create([
                    'title'       => $title,
                    'parent_type' => 'Organisation',
                    'parent_id'   => 1,
                    'data'        => '{}',
                    'compiled'    => json_decode(file_get_contents($path . '/' . $fileName), true),
                ]);
            }
        }
    }
}
