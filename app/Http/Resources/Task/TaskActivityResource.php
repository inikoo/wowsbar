<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:17:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property numeric $number_banners
 */
class TaskActivityResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Models\Task\TaskActivity $taskActivity */
        $taskActivity = $this;

        return [
            'author_name'   => $taskActivity->author->contact_name,
            'activity_name' => $taskActivity->activity->slug,
            'task'          => $taskActivity->task->type->name,
            'date'          => $taskActivity->date
        ];
    }
}
