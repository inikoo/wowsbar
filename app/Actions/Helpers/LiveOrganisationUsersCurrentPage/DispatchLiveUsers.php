<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\LiveOrganisationUsersCurrentPage;

use App\Events\BroadcastLiveUsers;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class DispatchLiveUsers
{
    use AsAction;
    use AsCommand;

    public function handle(ActionRequest $request): void
    {
        $organisationUser = $request->user();

        StoreOrganisationLiveUsersCurrentPage::run($request->all());

        broadcast(new BroadcastLiveUsers($request->all(), $organisationUser))->toOthers();
    }

    public function asController(ActionRequest $request): void
    {
        $this->handle($request);
    }
}
