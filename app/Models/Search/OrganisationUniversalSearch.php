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
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $section
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $model
 * @method static Builder|OrganisationUniversalSearch newModelQuery()
 * @method static Builder|OrganisationUniversalSearch newQuery()
 * @method static Builder|OrganisationUniversalSearch query()
 * @method static Builder|OrganisationUniversalSearch whereCreatedAt($value)
 * @method static Builder|OrganisationUniversalSearch whereDescription($value)
 * @method static Builder|OrganisationUniversalSearch whereId($value)
 * @method static Builder|OrganisationUniversalSearch whereModelId($value)
 * @method static Builder|OrganisationUniversalSearch whereModelType($value)
 * @method static Builder|OrganisationUniversalSearch whereSection($value)
 * @method static Builder|OrganisationUniversalSearch whereTitle($value)
 * @method static Builder|OrganisationUniversalSearch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationUniversalSearch extends Model
{
    use Searchable;

    protected $guarded = [];

    public function searchableAs(): string
    {
        return config('app.organisation_universal_search_index');
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
