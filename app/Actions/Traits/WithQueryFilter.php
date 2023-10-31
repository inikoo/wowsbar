<?php

namespace App\Actions\Traits;

use App\Models\Helpers\Query;

trait WithQueryFilter
{
    public function queryFilter(Query $query)
    {
        return $query->model_type::{$query->base}->{$query->filters};
    }
}
