<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\HumanResources\Employee;

use App\Models\Media\ExcelUpload;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreEmployeeUploads
{
    use AsAction;
    use WithAttributes;

    /**
     * @var true
     */
    private bool $asAction = false;

    public function handle(array $modelData): Model
    {
        return ExcelUpload::create([
            'type' => $modelData['type'],
            'original_filename' => $modelData['original_filename'],
            'filename'          => $modelData['filename'],
            'uploaded_at'       => now()
        ]);
    }
}
