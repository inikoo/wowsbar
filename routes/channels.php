<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 07 Dec 2023 14:32:48 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

use App\Models\Auth\OrganisationUser;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('org.personal.{userID}', function (OrganisationUser $user, int $userID) {
    return $userID===$user->id;
});

Broadcast::channel('org.general', function (OrganisationUser $user, int $userID) {
    return true;
});
