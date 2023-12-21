<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 18:48:13 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Leads\Prospect;

use App\Actions\InertiaAction;
use App\Models\Leads\Prospect;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsObject;

class SearchProspect extends InertiaAction
{
    use AsObject;

    public function handle(ActionRequest $request): array
    {
        $selectOptions = [];

        $needle=$request->get('q');
        if($needle === null) {
            return $selectOptions;
        }

        /** @var Prospect $prospects */
        $prospectsQuery = Prospect::whereWith('contact_name', $request->get('q'))->orWhereWith('company_name', $request->get('q'))->orWhereStartWith('email', $request->get('q'));
        $prospects      = $prospectsQuery->paginate();

        foreach ($prospects as $prospect) {
            $selectOptions[$prospect->id] =
                [
                    'id'                => $prospect->id,
                    'slug'              => $prospect->slug,
                    'name'              => $prospect->name,
                    'email'             => $prospect->email,
                    'phone'             => $prospect->phone,
                    'websites'          => $prospect->websites,
                    'state'             => $prospect->state,
                    'last_contacted_at' => $prospect->last_contacted_at,
                ];
        }

        return $selectOptions;
    }
}
