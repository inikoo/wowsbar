<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 15:06:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Search\UniversalSearch\UI\Customer;

use App\Actions\InertiaAction;
use App\Http\Resources\UniversalSearch\Customers\UniversalSearchResource;
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
            ->where('customer_id', customer()->id)
            ->get();
    }




    public function asController(ActionRequest $request): AnonymousResourceCollection
    {

        $searchResults=$this->handle($request->input('q', ''));
        return UniversalSearchResource::collection($searchResults);

    }


}
