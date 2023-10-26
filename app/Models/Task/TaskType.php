<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Task\TaskType
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskType extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(16);
    }
}
