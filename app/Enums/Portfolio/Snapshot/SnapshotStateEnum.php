<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 25 Jul 2023 12:56:21 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\Portfolio\Snapshot;

use App\Enums\EnumHelperTrait;

enum SnapshotStateEnum: string
{
    use EnumHelperTrait;

    case UNPUBLISHED = 'unpublished';
    case LIVE        = 'live';
    case HISTORIC    = 'historic';

    public static function labels(): array
    {
        return [
            'unpublished'  => __('Unpublished'),
            'live'         => __('Live'),
            'historic'     => __('Historic')
        ];
    }
}
