<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 23 Oct 2023 19:23:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Actions\Traits;

use App\Models\Organisation\Division;
use Illuminate\Support\Facades\Cache;

trait WithDivision
{
    public function getCachedDivisionId($divisionName)
    {
        $cacheKey   ='division_id_'.$divisionName;
        $divisionId = Cache::get($cacheKey);

        if(!$divisionId) {
            $divisionId = Division::firstWhere('slug', $divisionName)->id;
            Cache::put($cacheKey, $divisionId);
        }
        return $divisionId;
    }
}
