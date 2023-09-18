<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Prospect;

use App\Models\Media\ExcelUpload;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreProspectUploads
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
            'type'              => $modelData['type'],
            'original_filename' => $modelData['original_filename'],
            'filename'          => $modelData['filename'],
            'uploaded_at'       => now()
        ]);
    }
}
