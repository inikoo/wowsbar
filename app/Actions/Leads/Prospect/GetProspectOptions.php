<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\InertiaAction;
use App\Models\Helpers\Tag;
use App\Models\Leads\Prospect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class GetProspectOptions extends InertiaAction
{
    use AsObject;

    public function handle(ActionRequest $request): array
    {
        $selectOptions = [];
        /** @var \App\Models\Leads\Prospect $prospects */
        $prospects = Prospect::where('name', 'ILIKE', '%'.$request->get('q').'%')->get();

        foreach ($prospects as $prospect) {
            $selectOptions[$prospect->id] =
                [
                    'id'   => $prospect->id,
                    'slug' => $prospect->slug,
                    'name' => $prospect->name
                ];
        }

        return $selectOptions;
    }
}
