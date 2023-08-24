<?php

namespace App\Models\Portfolio;

use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use App\Models\Tenancy\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *  * @mixin \Eloquent
 */
class Snapshot extends Model
{
    use HasFactory;

    protected $casts = [
        'layout'  => 'array',
        'state' => SnapshotStateEnum::class
    ];

    protected $attributes = [
        'layout' => '{}'
    ];

    protected $guarded = [];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
