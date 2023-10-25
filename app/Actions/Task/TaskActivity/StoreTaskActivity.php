<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:19:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Task\TaskActivity;

use App\Models\Auth\Guest;
use App\Models\HumanResources\Employee;
use App\Models\Portfolio\SocialPost;
use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTaskActivity
{
    use AsAction;
    use WithAttributes;

    private bool $trusted = false;

    public function handle(Task $task, array $modelData, Employee|Guest $parent = null, SocialPost $activity = null): Model
    {
        data_set($modelData, 'task_id', $task->id);

        if($parent) {
            data_set($modelData, 'author_id', $parent->id);
            data_set($modelData, 'author_type', $parent::class);
        }

        if($activity) {
            data_set($modelData, 'activity_id', $activity->id);
            data_set($modelData, 'activity_type', $activity::class);
        }

        return $task->activities()->create($modelData);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->trusted) {
            return true;
        }

        return $request->user()->hasPermissionTo("sysadmin.edit");
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'string']
        ];
    }

    public function inEmployee(Task $task, Employee $employee, ActionRequest $request): Model
    {
        $request->validate();
        $modelData = $request->validated();

        return $this->handle($task, $modelData, $employee);
    }

    public function inGuest(Task $task, Guest $guest, ActionRequest $request): Model
    {
        $request->validate();
        $modelData = $request->validated();

        return $this->handle($task, $modelData, $guest);
    }

    public function action(Task $task, array $objectData): Model
    {
        $this->trusted = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($task, $validatedData);
    }
}
