<?php

namespace App\Models;

use App\Enums\Portfolio\Announcement\AnnouncementStateEnum;
use App\Models\Helpers\Deployment;
use App\Models\Helpers\Snapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Announcement
 *
 * @property int $id
 * @property string $code
 * @property string $ulid
 * @property string $name
 * @property string|null $icon
 * @property array $fields
 * @property array $container_properties
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $unpublished_snapshot_id
 * @property int|null $live_snapshot_id
 * @property string|null $ready_at
 * @property string|null $live_at
 * @property string|null $closed_at
 * @property string|null $published_checksum
 * @property AnnouncementStateEnum $state
 * @property bool $is_dirty
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Deployment> $deployments
 * @property-read int|null $deployments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read Snapshot|null $unpublishedSnapshot
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereContainerProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereIsDirty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereLiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereLiveSnapshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement wherePublishedChecksum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereReadyAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUnpublishedSnapshotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Announcement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts   = [
        "container_properties" => "array",
        "fields"               => "array",
        'state'                => AnnouncementStateEnum::class
    ];

    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'parent');
    }
    public function unpublishedSnapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class, 'unpublished_snapshot_id');
    }

    public function deployments(): MorphMany
    {
        return $this->morphMany(Deployment::class, 'model');
    }
}
