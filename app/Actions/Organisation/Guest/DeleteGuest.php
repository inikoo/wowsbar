<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 12 Sep 2023 13:51:25 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\Guest;

use App\Models\Auth\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteGuest
{
    use AsController;
    use WithAttributes;

    public function handle(Guest $guest): Guest
    {
        $guest->delete();

        return $guest;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("sysadmin.edit");
    }

    public function asController(Guest $guest, ActionRequest $request): Guest
    {
        $request->validate();

        return $this->handle($guest);
    }

    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('sysadmin.guests.index');
    }

}
