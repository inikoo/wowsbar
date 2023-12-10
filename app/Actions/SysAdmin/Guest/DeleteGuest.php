<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 13:51:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\SysAdmin\Guest;

use App\Actions\SysAdmin\Organisation\Hydrators\OrganisationHydrateGuests;
use App\Actions\SysAdmin\OrganisationUser\DeleteOrganisationUser;
use App\Models\Auth\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteGuest
{
    use AsAction;
    use WithAttributes;


    private bool $isAction=false;

    public function handle(Guest $guest): Guest
    {
        $guest->delete();
        DeleteOrganisationUser::run($guest->organisationUser);
        OrganisationHydrateGuests::dispatch();

        return $guest;
    }

    public function authorize(ActionRequest $request): bool
    {
        if($this->isAction) {
            return true;
        }
        return $request->user()->hasPermissionTo("sysadmin.edit");
    }

    public function asController(Guest $guest, ActionRequest $request): Guest
    {
        $request->validate();

        return $this->handle($guest);
    }

    public function action(Guest $guest): Guest
    {
        $this->isAction=true;
        $this->validateAttributes();
        return $this->handle($guest);
    }

    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('sysadmin.guests.index');
    }

}
