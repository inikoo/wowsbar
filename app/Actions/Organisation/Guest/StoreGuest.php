<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 14:19:22 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Guest;

use App\Actions\Organisation\Auth\OrganisationUser\StoreOrganisationUser;
use App\Actions\Organisation\Guest\Hydrators\GuestHydrateUniversalSearch;
use App\Actions\Organisation\Organisation\Hydrators\OrganisationHydrateGuests;
use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\Auth\Guest;
use App\Models\Auth\Role;
use App\Rules\AlphaDashDot;
use Illuminate\Console\Command;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
        $guest = Guest::create(
            Arr::except($modelData, [
                'username'
            ])
        );
        OrganisationHydrateGuests::dispatch();
        GuestHydrateUniversalSearch::dispatch($guest);

        StoreOrganisationUser::run(
            $guest,
            [
                'username' => Arr::get($modelData, 'username'),
                'password' => (app()->isLocal() ? 'hello' : wordwrap(Str::random(), 4, '-', true))
            ]
        );


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
            'type'         => ['required', Rule::in(GuestTypeEnum::values())],
            'username'     => ['required', new AlphaDashDot(), 'unique:App\Models\Auth\OrganisationUser,username', Rule::notIn(['export', 'create'])],
            'company_name' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'phone'        => ['nullable', 'phone:AUTO'],
            'email'        => ['nullable', 'email'],


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

    public string $commandSignature = 'org:create-guest {name} {username}  {type : Guest type contractor|external_employee|external_administrator} {--P|password=} {--e|email=} {--t|phone=}  {--identity_document_number=} {--identity_document_type=}';


    public function asCommand(Command $command): int
    {
        $this->trusted = true;


        $this->fill([
            'type'         => $command->argument('type'),
            'contact_name' => $command->argument('name'),
            'email'        => $command->option('email'),
            'phone'        => $command->option('phone'),
            'username'     => $command->argument('username'),
            'password'     => $command->option('password') ?? (app()->isLocal() ? 'hello' : wordwrap(Str::random(), 4, '-', true))
        ]);

        $guest = $this->handle($this->validateAttributes());

        if ($command->argument('type') == GuestTypeEnum::EXTERNAL_ADMINISTRATOR->value) {
            $superAdminRole = Role::where('guard_name', 'org')->where('name', 'super-admin')->firstOrFail();
            $guest->organisationUser->assignRole($superAdminRole);
        }

        $command->info("Guest <fg=yellow>$guest->slug</> created 👍");


        return 0;
    }

    public function htmlResponse(Guest $guest): RedirectResponse
    {
        return Redirect::route('org.sysadmin.guests.show', $guest->slug);
    }

}
