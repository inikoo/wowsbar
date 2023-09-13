<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 13 Aug 2023 15:57:35 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Common\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class ShowLogin
{
    use AsController;


    public function handle(ActionRequest $request): Response
    {
        return Inertia::render(
            match ($request->route()->getName()) {
                'org.login'    => 'Auth/Login',
                'public.login' => 'Public/Auth/Login',
                default        => 'Auth/Login'
            },
            [
                'status' => session('status'),
            ]
        );
    }

}
