<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 29 Sep 2023 10:17:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Organisation\OrganisationUser\UI;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class ShowResetPasswordUsers
{
    use AsController;

    public function handle(): Response
    {
        return Inertia::render(
            'Auth/ForgotPassword',
            [
                'status'    => session('status'),
            ]
        );
    }

}
