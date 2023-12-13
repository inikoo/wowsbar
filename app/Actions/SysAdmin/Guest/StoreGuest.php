<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:19:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Guest;

use App\Actions\HumanResources\SyncJobPosition;
use App\Actions\SysAdmin\Guest\Hydrators\GuestHydrateUniversalSearch;
use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateGuests;
use App\Actions\SysAdmin\OrganisationUser\StoreOrganisationUser;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Auth\Guest;
use App\Models\HumanResources\JobPosition;
use App\Rules\AlphaDashDot;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class StoreGuest
{
    use AsAction;
    use WithAttributes;

    private bool $trusted = false;

    public function handle(array $modelData): Guest
    {
        $positions = Arr::get($modelData, 'positions', []);
        Arr::forget($modelData, 'positions');


        $guest = Guest::create(
            Arr::except($modelData, [
                'username',
                'password',
                'reset_password'
            ])
        );
        OrganisationHydrateGuests::dispatch();
        GuestHydrateUniversalSearch::dispatch($guest);

        $organisationUserData=[
            'username'       => Arr::get($modelData, 'username'),
            'password'       => Arr::get($modelData, 'password'),
            'contact_name'   => $guest->contact_name,
            'email'          => $guest->email,
            'reset_password' => Arr::get($modelData, 'reset_password', false),
        ];

        if (Arr::get($guest->data, 'bulk_import')) {
            $organisationUserData['data'] = [
                'bulk_import' => Arr::get($guest->data, 'bulk_import')
            ];
        }

        StoreOrganisationUser::make()->action(
            $guest,
            $organisationUserData
        );

        $jobPositions = [];
        foreach ($positions as $position) {
            $jobPosition    = JobPosition::firstWhere('slug', $position);
            $jobPositions[] = $jobPosition->id;
        }
        SyncJobPosition::run($guest, $jobPositions);


        return $guest;
    }

    public function authorize(ActionRequest $request): bool
    {
        if ($this->trusted) {
            return true;
        }

        return $request->user()->hasPermissionTo("sysadmin.edit");
    }

    public function prepareForValidation(): void
    {
        if ($this->get('phone')) {
            $this->set('phone', preg_replace('/[^0-9+]/', '', $this->get('phone')));
        }
    }

    public function rules(): array
    {
        return [
            'type'           => ['required', Rule::in(GuestTypeEnum::values())],
            'alias'          => ['required', 'iunique:guests', 'string', 'max:12'],
            'username'       => ['required', 'required', new AlphaDashDot(), 'iunique:organisation_users'],
            'company_name'   => ['nullable', 'string', 'max:255'],
            'contact_name'   => ['required', 'string', 'max:255'],
            'phone'          => ['nullable', 'phone:AUTO'],
            'email'          => ['nullable', 'email'],
            'positions.*'    => ['exists:job_positions,slug'],
            'password'       => ['sometimes', 'required', 'max:255', app()->isLocal() || app()->environment('testing') ? null : Password::min(8)->uncompromised()],
            'reset_password' => ['sometimes', 'boolean'],
            'data'           => ['sometimes', 'array'],

        ];
    }

    public function asController(ActionRequest $request): Guest
    {
        $request->validate();
        $modelData = $request->validated();

        return $this->handle(Arr::except($modelData, ['username']));
    }


    public function action(array $objectData): Guest
    {
        $this->trusted = true;
        $this->setRawAttributes($objectData);
        $validatedData = $this->validateAttributes();

        return $this->handle($validatedData);
    }

    public string $commandSignature = 'guest:create {name} {alias} {type : Guest type contractor|external_employee|external_administrator} {--P|password=} {--e|email=} {--t|phone=} {--identity_document_number=} {--identity_document_type=}';


    public function asCommand(Command $command): int
    {
        $this->trusted = true;

        $fields = [
            'type'         => $command->argument('type'),
            'contact_name' => $command->argument('name'),
            'email'        => $command->option('email'),
            'phone'        => $command->option('phone'),
            'alias'        => $command->argument('alias'),
            'username'     => $command->argument('alias'),
            'password'     => $command->option('password') ?? (app()->isLocal() ? 'hello' : wordwrap(Str::random(), 4, '-', true))
        ];

        if ($command->argument('type') == GuestTypeEnum::EXTERNAL_ADMINISTRATOR->value) {
            $fields['positions'] = ['admin'];
        }


        $this->fill($fields);

        $guest = $this->handle($this->validateAttributes());


        $command->info("Guest <fg=yellow>$guest->slug</> created 👍");


        return 0;
    }

    public function htmlResponse(Guest $guest): RedirectResponse
    {
        return Redirect::route('org.sysadmin.guests.show', $guest->slug);
    }

}
