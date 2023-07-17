<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 17 Jul 2023 14:54:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Search\UniversalSearch\UI;

use App\Actions\InertiaAction;
use App\Http\Resources\UniversalSearch\UniversalSearchResource;
use App\Models\Search\UniversalSearch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class IndexUniversalSearch extends InertiaAction
{
    use AsController;


    public function handle(string $query): Collection
    {
        return UniversalSearch::search($query)
            ->within(UniversalSearch::make()->searchableAs().'_'.app('currentTenant')->slug)
            ->get()
            ->load('model');

    }

    public function asController(ActionRequest $request): AnonymousResourceCollection
    {

        $searchResults=$this->handle($request->input('q',''));
        return UniversalSearchResource::collection($searchResults);

    }


}
