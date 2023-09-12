<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 16 Aug 2023 08:09:28 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tenant\Portfolio\Uploads;

use App\Models\Portfolio\WebsiteUpload;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class UpdatePortfolioWebsiteUploads
{
    use AsAction;
    use WithAttributes;

    public function handle(WebsiteUpload $websiteUpload, array $data): WebsiteUpload
    {
        $websiteUpload->update($data);

        return $websiteUpload;
    }
}
