<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 04 Aug 2023 15:04:04 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\UI\Landlord\Auth;

use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsController;

class ShowLandlordLogin
{
    use AsController;


    public function handle(): Response
    {
        return Inertia::render('Landlord/Auth/LandlordLogin', [
        ]);
    }

}
