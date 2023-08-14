<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 20:01:15 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public;

use App\Models\Auth\PublicUser;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPublicLayout
{
    use AsAction;

    public function handle(PublicUser $user): array
    {
        $navigation = [];

        $navigation['dashboard'] = [
            'scope' => 'portfolio',
            'icon'  => ['fal', 'fa-home'],
            'label' => __('dashboard'),
            'route' => 'portfolio.dashboard'
        ];

        $navigation['banner'] = [
            'label' => __('banner'),
            'icon'  => ['fal', 'fa-briefcase'],
            'route' => 'sysadmin.dashboard',
        ];


        return [
            'navigation' => $navigation,
        ];
    }
}
