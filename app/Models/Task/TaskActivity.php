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

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
