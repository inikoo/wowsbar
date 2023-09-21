<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Uploads;

use App\Models\Media\ExcelUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreExcelUploads
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;

    public function handle($file, $class): ExcelUpload
    {
        $filename = $file->hashName();
        $type     = class_basename($class);
        $path     = 'org/' . Str::lower($type);
        Storage::disk('local')->put($path, $file);

        return ExcelUpload::create([
            'type'              => $type,
            'original_filename' => $file->getClientOriginalName(),
            'filename'          => $filename,
            'path'              => $path,
            'uploaded_at'       => now()
        ]);
    }
}
