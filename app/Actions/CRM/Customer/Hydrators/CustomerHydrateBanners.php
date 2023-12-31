<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\CRM\Customer\Hydrators;

use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Models\CRM\Customer;
use Lorisleiva\Actions\Concerns\AsAction;

class CustomerHydrateBanners
{
    use AsAction;


    public function handle(Customer $customer): void
    {
        $stats = [
            'number_banners'            => $customer->banners()->count(),
        ];

        foreach (BannerStateEnum::cases() as $state) {
            $stats['number_banners_state_'.$state->snake()] = $customer->banners()->where('state', $state->value)->count();
        }


        $customer->portfolioStats()->update($stats);
    }

}
