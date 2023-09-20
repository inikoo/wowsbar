<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 13 Aug 2023 15:57:35 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Common\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class ShowLogin
{
    use AsController;


    public function handle(): Response
    {
        return Inertia::render(
            'Auth/Login',
            [
                'status' => session('status'),
            ]
        );
    }

}
