<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\InertiaAction;
use App\Http\Resources\CRM\ProspectResource;
use App\Models\Leads\Prospect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetProspect extends InertiaAction
{
    use AsObject;

    public function handle(Prospect $prospect): array
    {

        return ProspectResource::make($prospect)->getArray();
    }

    public function asController(Prospect $prospect, ActionRequest $request): \Illuminate\Http\Response|array
    {
        return $this->handle($prospect);
    }

}
