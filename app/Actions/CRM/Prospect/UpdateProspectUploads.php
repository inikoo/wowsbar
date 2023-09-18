<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Prospect;

use App\Models\Media\ExcelUpload;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdateProspectUploads
{
    use AsAction;
    use WithAttributes;

    public function handle(ExcelUpload $excelUpload, array $data): ExcelUpload
    {
        $excelUpload->update($data);

        return $excelUpload;
    }
}
