<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Models\Portfolio\Banner;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateBanners
{
    use AsAction;


    public function handle(): void
    {
        $stats = [
            'number_banners'            => Banner::count(),
        ];

        foreach (BannerStateEnum::cases() as $state) {
            $stats['number_banners_state_'.$state->snake()] =Banner::where('state', $state->value)->count();
        }


        organisation()->portfoliosStats()->update($stats);
    }

}
