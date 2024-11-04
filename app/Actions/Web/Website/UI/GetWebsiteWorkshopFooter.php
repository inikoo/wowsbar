<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:35:41 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website\UI;

use App\Models\Portfolio\PortfolioWebsite;
use App\Models\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class GetWebsiteWorkshopFooter
{
    use AsAction;

    public function handle(PortfolioWebsite $portfolioWebsite): array
    {
        return [
           'data'=> Arr::get($portfolioWebsite->compiled_layout, 'footer')
        ];
    }
}
