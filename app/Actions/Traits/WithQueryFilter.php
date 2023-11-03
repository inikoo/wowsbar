<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 17:21:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\Helpers\Query;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait WithQueryFilter
{
    public function queryFilter(Query $query): Model
    {
        /** @var Model $model */
        $model = $query->model_type::query();

        if(! blank($query->base)) {
            if($property = Arr::get($query->base, 'with')) {
                $model->whereNotNull($property);
            }

            if($property = Arr::get($query->base, 'without')) {
                $model->whereNotNull($property);
            }
        }

        if(! blank($query->filters)) {
            if($property = Arr::get($query->filters, 'with')) {
                $model->orWhereNotNull($property);
            }

            if($property = Arr::get($query->filters, 'without')) {
                $model->orWhereNull($property);
            }
        }

        return $model;
    }
}
