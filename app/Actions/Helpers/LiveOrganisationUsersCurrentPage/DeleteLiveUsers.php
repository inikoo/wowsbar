<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 21 Sep 2023 11:34:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\LiveOrganisationUsersCurrentPage;

use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class DeleteLiveUsers
{
    use AsAction;
    use AsCommand;

    public function handle(): bool
    {
        return Cache::delete('live_users');
    }

    public function asController(): bool
    {
        return $this->handle();
    }
}
