<?php
/*
 * Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
 * Created: Mon, 13 Mar 2023 08:38:40 Central European Standard Time, Malaga, Spain
 * Copyright (c) 2023, Inikoo LTD
 */

namespace App\Models\Traits;

use App\Models\Search\OrganisationUniversalSearch;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasOrganisationUniversalSearch
{
    public function universalSearch(): MorphOne
    {
        return $this->morphOne(OrganisationUniversalSearch::class, 'model');
    }
}
