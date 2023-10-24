<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:19:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Task\TaskActivity;

use App\Models\Auth\Guest;
use App\Models\HumanResources\Employee;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreTaskActivity
{
    use AsAction;
    use WithAttributes;

    private bool $trusted = false;

    public function handle(Employee|Guest $parent, array $modelData): Model
    {
        return $parent->tasks()->create($modelData);
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

    public function inEmployee(Employee $employee, ActionRequest $request): Model
    {
        $request->validate();
        $modelData = $request->validated();

        return $this->handle($employee, $modelData);
    }

    public function inGuest(Guest $guest, ActionRequest $request): Model
    {
        $request->validate();
        $modelData = $request->validated();

        return $this->handle($guest, $modelData);
    }

    public function action(Employee|Guest $parent, array $objectData): Model
    {
        $this->trusted = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($parent, $validatedData);
    }
}
