<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 13 Oct 2023 09:44:25 Malaysia Time, Office, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Enums\UI\Customer;

use App\Enums\EnumHelperTrait;
use App\Enums\HasTabs;

enum ProspectsWebsitesTabsEnum: string
{
    use EnumHelperTrait;
    use HasTabs;

    case WEBSITES             = 'websites';

    public function blueprint(): array
    {
        return match ($this) {
            ProspectsWebsitesTabsEnum::WEBSITES => [
                'title' => __('websites'),
                'icon'  => 'fal fa-globe',
            ],


        };
    }
}
