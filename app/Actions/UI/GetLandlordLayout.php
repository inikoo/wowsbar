<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 07 Sept 2022 22:03:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\UI;

use App\Models\Auth\RootUser;
use Lorisleiva\Actions\Concerns\AsAction;

class GetLandlordLayout
{
    use AsAction;

    public function handle(RootUser $user): array
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
