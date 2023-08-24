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

    case ON_PUBLISHED = 'on-published';
    case ONLINE = 'online';
    case HISTORIC = 'historic';

    public static function labels(): array
    {
        return [
            'on-published' => __('On Published'),
            'online'       => __('Online'),
            'historic'     => __('Historic')
        ];
    }
}
