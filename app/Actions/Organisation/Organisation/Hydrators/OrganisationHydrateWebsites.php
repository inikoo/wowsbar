<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 12:15:47 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Models\Organisation\Web\Website;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateWebsites
{
    use AsAction;

    public function handle(): void
    {

        $stats = [
            'number_websites' => Website::count()
        ];

        $websiteStateCount = Website::selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state')->all();
        foreach (WebsiteStateEnum::cases() as $websiteState) {
            $stats['number_websites_state_'.$websiteState->snake()] = Arr::get($websiteStateCount, $websiteState->value, 0);
        }


        organisation()->stats()->update($stats);
    }
}
