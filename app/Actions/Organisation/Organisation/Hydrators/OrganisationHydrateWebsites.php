<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Models\Web\Website;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateWebsites
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {

        $stats = [
            'number_websites' => Website::count()
        ];

        array_merge($stats, $this->getEnumStats('websites', 'state', WebsiteStateEnum::class, Website::class));


        organisation()->stats()->update($stats);
    }
}
