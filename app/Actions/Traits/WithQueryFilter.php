<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 17:21:03 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\Helpers\Query;

trait WithQueryFilter
{
    public function queryFilter(Query $query)
    {
        // return $query->model_type::{$query->base}->{$query->filters};
    }
}
