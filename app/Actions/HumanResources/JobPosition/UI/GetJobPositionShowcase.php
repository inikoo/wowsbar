<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\JobPosition\UI;

use App\Models\HumanResources\JobPosition;
use Lorisleiva\Actions\Concerns\AsObject;

class GetJobPositionShowcase
{
    use AsObject;

    public function handle(JobPosition $jobPosition): array
    {
        return [
            [

            ]
        ];
    }
}
