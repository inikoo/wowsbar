<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:19:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tasks\Task;

use App\Models\Auth\Guest;
use App\Models\HumanResources\Employee;
use App\Models\Tasks\Task;
use App\Models\Tasks\TaskType;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTask
{
    use AsAction;
    use WithAttributes;

    private bool $trusted = false;

    public function handle(TaskType $taskType, array $modelData): Model
    {
        /** @var Task $task */
        $task= $taskType->tasks()->create($modelData);
        return $task;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->trusted) {
            return true;
        }
        return $request->user()->hasPermissionTo("tasks.edit");
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
