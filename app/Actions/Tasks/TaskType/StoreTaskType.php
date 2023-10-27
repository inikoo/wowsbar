<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:19:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Tasks\TaskType;

use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateTaskTypes;
use App\Models\Organisation\Division;
use App\Models\Tasks\TaskType;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTaskType
{
    use AsAction;
    use WithAttributes;

    private bool $trusted = false;

    public function handle(Division $division, array $modelData): TaskType
    {
        /** @var TaskType $taskType */
        $taskType= $division->taskTypes()->create($modelData);
        $taskType->taskStats()->create();
        OrganisationHydrateTaskTypes::dispatch();
        return $taskType;
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
            'name' => ['required', 'string']
        ];
    }

    public function asController(Division $division, ActionRequest $request): TaskType
    {
        $request->validate();
        $modelData = $request->validated();

        return $this->handle($division, $modelData);
    }

    public function action(Division $division, array $objectData): TaskType
    {
        $this->trusted = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($division, $validatedData);
    }
}
