<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:12 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\HumanResources\Workplace;

use App\Actions\Helpers\Address\StoreAddressAttachToModel;
use App\Actions\HumanResources\Workplace\Hydrators\WorkplaceHydrateUniversalSearch;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateWorkplaces;
use App\Models\HumanResources\Workplace;
use App\Rules\ValidAddress;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreWorkplace
{
    use AsAction;
    use WithAttributes;

    private bool $asAction = false;


    public function handle(array $modelData, array $addressData): Workplace
    {
        $workplace               = Workplace::create($modelData);
        StoreAddressAttachToModel::run($workplace, $addressData, ['scope' => 'contact']);

        $workplace->location = $workplace->getLocation();
        $workplace->save();
        $workplace->stats()->create();
        OrganisationHydrateWorkplaces::run();
        WorkplaceHydrateUniversalSearch::dispatch($workplace);

        return $workplace;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->asAction) {
            return true;
        }
        return $request->user()->hasPermissionTo("hr.edit");
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'max:255'],
            'type'       => ['required'],
            'address'    => ['required', new ValidAddress()]
        ];
    }

    public function asController(ActionRequest $request): Workplace
    {

        $request->validate();
        $validatedData=$request->validated();

        return $this->handle(
            modelData: Arr::except($validatedData, 'address'),
            addressData: Arr::only($validatedData, 'address')['address']
        );
    }

    public function htmlResponse(Workplace $workplace): RedirectResponse
    {
        return Redirect::route('org.hr.workplaces.show', $workplace->slug);
    }

    public string $commandSignature = 'workplace:create {name} {type}';

    public function asCommand(Command $command): int
    {
        $this->asAction = true;

        $this->setRawAttributes([
            'address' => [
                'country_id'=> organisation()->country_id
            ],
            'name' => $command->argument('name'),
            'type' => $command->argument('type'),

        ]);

        try {
            $validatedData = $this->validateAttributes();
        } catch (Exception $e) {
            $command->error($e->getMessage());

            return 1;
        }

        $workplace = $this->handle(
            modelData: Arr::except($validatedData, 'address'),
            addressData: Arr::only($validatedData, 'address')['address']
        );

        $command->info("Workplace $workplace->slug created successfully 🎉");

        return 0;
    }
}