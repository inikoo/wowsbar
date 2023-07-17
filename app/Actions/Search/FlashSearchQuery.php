<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 17 Oct 2022 17:54:17 British Summer Time, Sheffield, UK
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

namespace App\Actions\Search;

use App\Actions\InertiaAction;
use App\Http\Resources\UniversalSearch\UniversalSearchResource;
use App\Models\Search\UniversalSearch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\Concerns\AsController;

class FlashSearchQuery extends InertiaAction
{
    use AsController;

    public function asController(Request $request): AnonymousResourceCollection|array
    {
        $query = $request->get('q');

        if ($query) {
            $items = UniversalSearch::search($query)->paginate(5);

            return UniversalSearchResource::collection($items);
        }

        return ['data' => []];
    }
}
