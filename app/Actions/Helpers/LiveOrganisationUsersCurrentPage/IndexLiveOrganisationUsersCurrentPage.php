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

class IndexLiveOrganisationUsersCurrentPage
{
    use AsAction;
    use AsCommand;

    public function handle(): array
    {
        return Cache::get('live_organisation_users', []);
    }

    public function asController(): array
    {
        return $this->handle();
    }
}
