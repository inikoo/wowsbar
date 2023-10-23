<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 23 Oct 2023 15:46:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Http\Resources\HumanResources;

use App\Models\HumanResources\Employee;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $job_positions
 */
class EmployeeResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        /** @var Employee $employee */
        $employee = $this;


        return [
            'id'                  => $employee->id,
            'slug'                => $employee->slug,
            'contact_name'        => $employee->contact_name,
            'job_title'           => $employee->job_title,
            'worker_number'       => $employee->worker_number,
            'state'               => $employee->state,
            'employment_start_at' => $employee->employment_start_at,
            'employment_end_at'   => $employee->employment_end_at,
            'salary'              => $employee->salary,
            'user'                => $employee->organisationUser?->only('username', 'status'),
            'positions'           => JobPositionLightResource::collection($employee->jobPositions),
            'emergency_contact'   => $employee->emergency_contact,
            'state_icon'          => $employee->state->stateIcon()[$employee->state->value],
            'created_at'          => $employee->created_at,
            'updated_at'          => $employee->updated_at,
        ];
    }
}
