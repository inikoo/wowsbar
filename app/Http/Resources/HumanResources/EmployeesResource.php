<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 21 Oct 2021 12:37:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Http\Resources\HumanResources;

use App\Models\HumanResources\Employee;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @property mixed $job_positions
 */
class EmployeesResource extends JsonResource
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
            'positions'           => JobPositionLightResource::collection(json_decode($this->job_positions)),
            'state_icon'          => $employee->state->stateIcon()[$employee->state->value],
        ];
    }
}
