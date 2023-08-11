<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 10 Jul 2023 13:45:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Public\Auth;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class ShowPublicConfirmablePassword
{
    use AsController;

    public function handle(): Response|RedirectResponse
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    public function asController(): Response
    {
        return $this->handle();
    }
}
