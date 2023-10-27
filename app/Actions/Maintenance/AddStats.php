<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 18:56:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Maintenance;

use App\Models\Organisation\Division;
use Lorisleiva\Actions\Concerns\AsCommand;

class AddStats
{
    use AsCommand;

    public string $commandSignature = 'maintenance:add-stats';


    public function asCommand(): int
    {
        $organisation = organisation();
        if (!$organisation->taskStats) {
            $organisation->taskStats()->create();
        }

        foreach (Division::all() as $division) {
            if (!$division->taskStats) {
                $division->taskStats()->create();
            }
        }

        return 0;
    }
}
