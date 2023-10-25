<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 *
 * @property string $date
 * @property \App\Models\Task\Task $task
 * @property \App\Models\Portfolio\SocialPost $activity
 * @property \App\Models\HumanResources\Employee|\App\Models\Auth\Guest $author
 * @mixin \Eloquent
 */

class TaskActivity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function activity(): MorphTo
    {
        return $this->morphTo();
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
