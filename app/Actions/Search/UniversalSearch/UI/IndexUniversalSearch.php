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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class IndexUniversalSearch extends InertiaAction
{
    use AsController;


    public function handle(string $query): LengthAwarePaginator
    {
        return UniversalSearch::search($query)->paginate(10);
    }

    public function asController(ActionRequest $request): LengthAwarePaginator
    {


        return $this->handle($request->input('q',''));

    }

    public function jsonResponse(LengthAwarePaginator $searchResults): AnonymousResourceCollection
    {
        return UniversalSearchResource::collection($searchResults);
    }


}
