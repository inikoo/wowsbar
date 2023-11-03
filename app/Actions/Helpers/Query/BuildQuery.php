<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 03 Nov 2023 18:40:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Helpers\Query;

use App\Models\Helpers\Query;
use App\Models\Leads\Prospect;
use Lorisleiva\Actions\Concerns\AsObject;
use Spatie\QueryBuilder\QueryBuilder;

class BuildQuery
{
    use AsObject;

    public function handle(Query $query): QueryBuilder
    {
        $queryBuilder = QueryBuilder::for(Prospect::class);

        foreach ($query->constrains as $constrainsType => $constrainsData) {
            if ($constrainsType == 'and') {
                foreach ($constrainsData as $constrainType => $constrainData) {
                    if ($constrainType == 'with') {
                        $queryBuilder->whereNotNull($constrainData);
                    } elseif ($constrainType == 'without') {
                        $queryBuilder->whereNull($constrainData);
                    } elseif ($constrainType == 'where') {
                        $queryBuilder->where($constrainData[0], $constrainData[1], $constrainData[2]);
                    }
                }
            }
        }



        return $queryBuilder;
    }
}
