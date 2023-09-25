<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Uploads;

use App\Models\CRM\Customer;
use App\Models\Media\ExcelUpload;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StorePortfolioWebsiteUploads
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
            'original_filename' => $modelData['original_filename'],
            'filename'          => $modelData['filename'],
            'uploaded_at'       => now()
        ]);
    }
}
