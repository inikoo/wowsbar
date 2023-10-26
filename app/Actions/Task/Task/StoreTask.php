<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:19:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Task\Task;

use App\Models\Auth\OrganisationUser;
use App\Models\Task\Task;
use App\Models\Task\TaskType;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTask
{
    use AsAction;
    use WithAttributes;

    private bool $trusted = false;

    public function handle(OrganisationUser $organisationUser, TaskType $taskType, array $modelData): Task
    {
        data_set($modelData, 'organisation_user_id', $organisationUser->id);
        data_set($modelData, 'task_type_id', $taskType->id);

        return Task::create($modelData);
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
            'name' => ['required', 'string'],
            'code' => ['required', 'iunique:task_types', 'string', 'max:12'],
        ];
    }

    public function asController(OrganisationUser $organisationUser, TaskType $taskType, ActionRequest $request): Task
    {
        $request->validate();
        $modelData = $request->validated();

        return $this->handle($organisationUser, $taskType, $modelData);
    }

    public function action(OrganisationUser $organisationUser, TaskType $taskType, array $objectData): Task
    {
        $this->trusted = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($organisationUser, $taskType, $validatedData);
    }
}
