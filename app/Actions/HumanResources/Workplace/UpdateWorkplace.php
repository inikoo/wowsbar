<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Workplace;

use App\Actions\Helpers\Address\UpdateAddress;
use App\Actions\HumanResources\Workplace\Hydrators\WorkplaceHydrateUniversalSearch;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateWorkplaces;
use App\Actions\Traits\WithActionUpdate;
use App\Enums\HumanResources\Workplace\WorkplaceTypeEnum;
use App\Http\Resources\HumanResources\WorkplaceResource;
use App\Models\HumanResources\Workplace;
use App\Rules\ValidAddress;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;

class UpdateWorkplace
{
    use WithActionUpdate;

    public function handle(Workplace $workplace, array $modelData): Workplace
    {
        $addressData = Arr::get($modelData, 'address');
        Arr::forget($modelData, 'address');

        $workplace = $this->update($workplace, $modelData, ['data']);
        if ($workplace->wasChanged('type')) {
            OrganisationHydrateWorkplaces::run();
        }

        if ($addressData) {
            UpdateAddress::run($workplace->address, $addressData);
            $workplace->location = $workplace->address->getLocation();
            $workplace->save();
        }


        WorkplaceHydrateUniversalSearch::dispatch($workplace);

        return $workplace;
    }


    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function rules(): array
    {
        return [
            'name'    => ['sometimes', 'required', 'max:255'],
            'type'    => ['sometimes', 'required', Rule::enum(WorkplaceTypeEnum::class)],
            'address' => ['sometimes', 'array', new ValidAddress()]
        ];
    }

    public function asController(Workplace $workplace, ActionRequest $request): Workplace
    {
        $request->validate();
        $validated = $request->validated();
        if (array_key_exists('address', $validated)) {
            return $this->handle(
                $workplace,
                $validated
            );
        } else {
            return $this->handle($workplace, modelData: $validated);
        }
    }

    public function jsonResponse(Workplace $workplace): WorkplaceResource
    {
        return new WorkplaceResource($workplace);
    }
}
