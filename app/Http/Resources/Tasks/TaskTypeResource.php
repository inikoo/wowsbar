<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 26 Oct 2023 23:11:08 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Tasks;

use App\Models\Tasks\TaskType;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property numeric $number_banners
 */
class TaskTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var TaskType $taskType */
        $taskType = $this;

        return [
            'name'   => $taskType->name
        ];
    }
}
