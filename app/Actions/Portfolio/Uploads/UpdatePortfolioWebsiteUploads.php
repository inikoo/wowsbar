<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:42:14 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Portfolio\Uploads;

use App\Models\Media\ExcelUpload;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdatePortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    public function handle(ExcelUpload $websiteUpload, array $data): ExcelUpload
    {
        $websiteUpload->update($data);

        return $websiteUpload;
    }
}
