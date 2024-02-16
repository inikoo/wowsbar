<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 30 Oct 2023 16:11:55 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Html;

use Illuminate\Support\Facades\File;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;
use Spatie\Browsershot\Browsershot;

class GetImageFromHtml
{
    use AsAction;
    use WithAttributes;

    public function handle($html, $filename): array
    {
        $path = storage_path('app/tmp/screenshots/');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $filename = $filename.'.jpg';

        $shot = Browsershot::html($html)
            ->setIncludePath('$PATH:/usr/bin')
            ->fullPage()
            ->setOption('newHeadless', true)
            ->base64Screenshot();

        $image = str_replace('data:image/png;base64,', '', $shot);
        $image = str_replace(' ', '+', $image);

        File::put($path.$filename, base64_decode($image));

        return [
            'path'     => $path,
            'filename' => $filename,
            'fullPath' => $path.$filename
        ];
    }

}
