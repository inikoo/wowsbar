<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 13:07:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Laravel\Scout\Searchable;


class UniversalSearch extends Model
{
    use Searchable;

    protected $guarded = [];

    public function searchableAs(): string
    {
        $index = array_filter([config('app.name'), App::environment('production') ? null : App::environment(), app('currentTenant')->slug, 'universal_search']);
        return implode('_', $index);
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['updated_at', 'created_at']);
    }
}
