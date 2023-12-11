<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\LiveUsers;

use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class StoreLiveUsers
{
    use AsAction;
    use AsCommand;

    public function handle($user, array $data): bool
    {
        $currentLiveUsers = IndexLiveUsers::run();

        data_set($data, 'last_active', now());

        return Cache::put('live_users', array_merge($currentLiveUsers, [$data]));
    }

    public function asController(ActionRequest $request): bool
    {
        $organisationUser = $request->user();

        return $this->handle($organisationUser, $request->all());
    }
}