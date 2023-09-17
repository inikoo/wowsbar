<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 13:07:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

/**
 * App\Models\Search\UniversalSearch
 *
 * @property int $id
 * @property int $tenant_id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $section
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $model
 * @property-read \App\Models\Tenancy\Tenant $tenant
 * @method static Builder|UniversalSearch newModelQuery()
 * @method static Builder|UniversalSearch newQuery()
 * @method static Builder|UniversalSearch query()
 * @method static Builder|UniversalSearch whereCreatedAt($value)
 * @method static Builder|UniversalSearch whereDescription($value)
 * @method static Builder|UniversalSearch whereId($value)
 * @method static Builder|UniversalSearch whereModelId($value)
 * @method static Builder|UniversalSearch whereModelType($value)
 * @method static Builder|UniversalSearch whereSection($value)
 * @method static Builder|UniversalSearch whereTenantId($value)
 * @method static Builder|UniversalSearch whereTitle($value)
 * @method static Builder|UniversalSearch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UniversalSearch extends Model
{
    use Searchable;

    protected $guarded = [];

    protected $table='universal_searches';

    public function searchableAs(): string
    {
        return config('elasticsearch.index_prefix') . config('app.env').'_search';
    }

    public function toSearchableArray(): array
    {
        return Arr::except($this->toArray(), ['updated_at', 'created_at']);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

}
