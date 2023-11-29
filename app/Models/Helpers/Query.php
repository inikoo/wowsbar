<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 31 Oct 2023 14:55:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Helpers\Query
 *
 * @property int $id
 * @property string $parent_type
 * @property int $parent_id
 * @property string $slug
 * @property string $name
 * @property string $model_type
 * @property array $constrains
 * @property array $compiled_constrains
 * @property bool $has_arguments
 * @property bool $is_seeded
 * @property int|null $number_items
 * @property string|null $counted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @property-read Model|\Eloquent $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Query newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Query newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Query query()
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereCompiledConstrains($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereConstrains($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereCountedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereHasArguments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereIsSeeded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereNumberItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereParentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Query whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Query extends Model
{
    use HasSlug;

    protected $casts = [
        'constrains'          => 'array',
        'compiled_constrains' => 'array',
        'informatics'         => 'array',
        'is_seeded'           => 'boolean',
        'has_arguments'       => 'boolean'
    ];

    protected $attributes = [
        'constrains'          => '{}',
        'compiled_constrains' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(64);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scope(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }
}
