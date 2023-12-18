<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:35:16 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Guest;

use App\Actions\HumanResources\SyncJobPosition;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateGuests;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Http\Resources\SysAdmin\GuestResource;
use App\Models\Auth\Guest;
use App\Models\HumanResources\JobPosition;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class UpdateGuest
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Guest $guest, array $modelData): Guest
    {
        if (Arr::exists($modelData, 'positions')) {
            $jobPositions=[];
            foreach (Arr::get($modelData, 'positions', []) as $position) {
                $jobPosition   = JobPosition::firstWhere('slug', $position);
                $jobPositions[]=$jobPosition->id;
            }
            SyncJobPosition::run($guest, $jobPositions);
            Arr::forget($modelData, 'positions');
        }


        $guest= $this->update($guest, $modelData, [
            'data',
        ]);

        if ($guest->wasChanged(['state'])) {
            OrganisationHydrateGuests::dispatch();
        }

        return $guest;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("sysadmin.edit");
    }

    public function rules(): array
    {
        return [
            'contact_name'             => ['sometimes', 'required', 'string', 'max:255'],
            'email'                    => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone'                    => ['sometimes', 'nullable', 'phone:AUTO'],
            'identity_document_number' => ['sometimes', 'nullable', 'string'],
            'identity_document_type'   => ['sometimes', 'nullable', 'string'],
            'type'                     => ['sometimes', 'required', Rule::in(GuestTypeEnum::values())],
            'positions'                => ['sometimes', 'required', 'array'],

        ];
    }


    public function asController(Guest $guest, ActionRequest $request): Guest
    {
        $request->validate();

        return $this->handle($guest, $request->validated());
    }

    public function action(Guest $guest, $objectData): Guest
    {
        $this->asAction = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($guest, $validatedData);
    }

    public function jsonResponse(Guest $guest): GuestResource
    {
        return new GuestResource($guest);
    }
}
