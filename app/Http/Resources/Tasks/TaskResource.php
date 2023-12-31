<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Tasks;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property numeric $number_banners
 */
class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Tasks\Task $task */
        $task = $this;

        return [
          //  'author_name'   => $task->author->contact_name,
         //   'activity_name' => $task->activity->slug,
         //   'task'          => $task->task->type->name,
            'date'          => $task->date
        ];
    }
}
