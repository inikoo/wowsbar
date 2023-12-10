<?php

namespace App\Models\Tasks;

use App\Models\SysAdmin\Division;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Tasks\TaskType
 *
 * @property int $id
 * @property int $division_id
 * @property string $slug
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Division $division
 * @property-read \App\Models\Tasks\TaskTypeStats|null $taskStats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tasks\Task> $tasks
 * @property-read int|null $tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaskType whereDivisionId($value)
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

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function taskStats(): HasOne
    {
        return $this->hasOne(TaskTypeStats::class);
    }

}
