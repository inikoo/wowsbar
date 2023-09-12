<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 13:51:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Guest;

use App\Actions\Traits\WithActionUpdate;
use App\Enums\Auth\Guest\GuestTypeEnum;
use App\Http\Resources\SysAdmin\GuestResource;
use App\Models\Organisation\Guest;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class UpdateGuest
{
    use WithActionUpdate;

    private bool $asAction = false;

    public function handle(Guest $guest, array $modelData): Guest
    {
        return $this->update($guest, $modelData, [
            'data',
        ]);
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }

        return $request->user()->hasPermissionTo("shops.customers.edit");
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

        ];
    }


    public function asController(Guest $guest, ActionRequest $request): Guest
    {
        $request->validate();

        return $this->handle($guest, $request->all());
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
