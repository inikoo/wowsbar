<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Organisation\Hydrators;

use App\Enums\Divisions\DivisionEnum;
use App\Models\Organisation\Division;
use App\Models\Task\TaskType;
use Lorisleiva\Actions\Concerns\AsAction;

class OrganisationHydrateTaskTypes
{
    use AsAction;


    public function handle(): void
    {
        $stats = [
            'number_task_types'            => TaskType::count(),
        ];

        foreach (DivisionEnum::cases() as $state) {
            $division                                             = Division::where('slug', $state->value)->first();
            $stats['number_task_types_division_'.$state->snake()] = TaskType::where('division_id', $division->id)->count();
        }

        organisation()->taskStats()->update($stats);
    }

}
