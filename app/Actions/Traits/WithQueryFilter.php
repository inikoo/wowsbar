<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 17:21:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\Helpers\Query;
use Illuminate\Database\Eloquent\Model;

trait WithQueryFilter
{
    public function queryFilter(Query $query): Model
    {
        /** @var Model $model */
        $model = $query->model_type::query();

        if(! blank($query->base)) {
            $model->{$query->base};
        }

        if(! blank($query->filters)) {
            foreach ($query->filters as $filter) {
                $model->{$filter};
            }
        }

        return $model;
    }
}
