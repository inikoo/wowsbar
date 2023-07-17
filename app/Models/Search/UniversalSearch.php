<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 12 Jul 2023 13:07:45 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Search;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;

/**
 * App\Models\Search\UniversalSearch
 *
 * @property int $id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $section
 * @property mixed|null $route
 * @property string|null $icon
 * @property string $primary_term
 * @property string|null $secondary_term
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|UniversalSearch newModelQuery()
 * @method static Builder|UniversalSearch newQuery()
 * @method static Builder|UniversalSearch query()
 * @method static Builder|UniversalSearch whereCreatedAt($value)
 * @method static Builder|UniversalSearch whereIcon($value)
 * @method static Builder|UniversalSearch whereId($value)
 * @method static Builder|UniversalSearch whereModelId($value)
 * @method static Builder|UniversalSearch whereModelType($value)
 * @method static Builder|UniversalSearch wherePrimaryTerm($value)
 * @method static Builder|UniversalSearch whereRoute($value)
 * @method static Builder|UniversalSearch whereSecondaryTerm($value)
 * @method static Builder|UniversalSearch whereSection($value)
 * @method static Builder|UniversalSearch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UniversalSearch extends Model
{
    use Searchable;
    use BelongsToTenant;



    protected $guarded = [];

    public function searchableAs(): string
    {
        return config('app.name').'_search';
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
