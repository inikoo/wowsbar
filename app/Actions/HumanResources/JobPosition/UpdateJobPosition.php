<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\JobPosition;

use App\Actions\Traits\WithActionUpdate;
use App\Http\Resources\HumanResources\JobPositionResource;
use App\Models\HumanResources\JobPosition;
use Lorisleiva\Actions\ActionRequest;

class UpdateJobPosition
{
    use WithActionUpdate;

    public function handle(JobPosition $jobPosition, array $modelData): JobPosition
    {
        return $this->update($jobPosition, $modelData, ['data']);
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function rules(): array
    {
        return [
            'code'      => ['sometimes','required', 'max:8'],
            'name'      => ['sometimes','required', 'max:255'],
        ];
    }

    public function asController(JobPosition $jobPosition, ActionRequest $request): JobPosition
    {
        $request->validate();

        return $this->handle($jobPosition, $request->validated());
    }


    public function jsonResponse(JobPosition $jobPosition): JobPositionResource
    {
        return new JobPositionResource($jobPosition);
    }
}
