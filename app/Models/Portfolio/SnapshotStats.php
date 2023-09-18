<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:24:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToCustomer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolio\SnapshotStats
 *
 * @property int $id
 * @property int $customer_id
 * @property int $snapshot_id
 * @property int $number_snapshots
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CRM\Customer $customer
 * @property-read \App\Models\Portfolio\Snapshot $snapshot
 * @method static Builder|SnapshotStats newModelQuery()
 * @method static Builder|SnapshotStats newQuery()
 * @method static Builder|SnapshotStats query()
 * @method static Builder|SnapshotStats whereCreatedAt($value)
 * @method static Builder|SnapshotStats whereCustomerId($value)
 * @method static Builder|SnapshotStats whereId($value)
 * @method static Builder|SnapshotStats whereNumberSnapshots($value)
 * @method static Builder|SnapshotStats whereSnapshotId($value)
 * @method static Builder|SnapshotStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SnapshotStats extends Model
{
    use BelongsToCustomer;

    protected $table   = 'snapshot_stats';
    protected $guarded = [];

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class);
    }
}
