<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 12:15:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Web\Website;

use App\Models\Web\Website;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;
use Lorisleiva\Actions\Concerns\WithAttributes;

class DeleteWebsite
{
    use AsController;
    use WithAttributes;

    public function handle(Website $website): Website
    {
        $website->delete();

        return $website;
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo("websites.edit");
    }

    public function asController(Website $website, ActionRequest $request): Website
    {
        $request->validate();
        return $this->handle($website);
    }



    public function htmlResponse(): RedirectResponse
    {
        return Redirect::route('web.websites.index');
    }

}
