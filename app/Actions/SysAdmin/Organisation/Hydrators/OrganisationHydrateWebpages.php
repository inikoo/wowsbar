<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 11:42:14 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Organisation\Hydrators;

use App\Actions\Traits\WithEnumStats;
use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageStateEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Web\Webpage;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateWebpages
{
    use AsAction;
    use WithEnumStats;

    public function handle(): void
    {
        $stats = [
            'number_webpages' => Webpage::count()
        ];

        array_merge($stats, $this->getEnumStats('webpages', 'type', WebpageTypeEnum::class, Webpage::class));
        array_merge($stats, $this->getEnumStats('webpages', 'purpose', WebpagePurposeEnum::class, Webpage::class));
        array_merge($stats, $this->getEnumStats('webpages', 'state', WebpageStateEnum::class, Webpage::class));


        organisation()->stats()->update($stats);
    }


}
