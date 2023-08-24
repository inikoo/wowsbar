<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:24:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Portfolio\SnapshotStats
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $snapshot_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Portfolio\Snapshot $snapshot
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats whereSnapshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SnapshotStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SnapshotStats extends Model
{
    use UsesTenantConnection;
    protected $table   = 'snapshot_stats';
    protected $guarded = [];

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
