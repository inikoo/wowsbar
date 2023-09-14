<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 14 Sep 2023 11:16:36 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Web\Website\UI;

use App\Models\Organisation\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsObject;

class GetWebsiteWorkshopLayout
{
    use AsObject;

    public function handle(Website $website): array
    {
        return [
            Arr::get($website->structure, 'layout')

        ];
    }
}
