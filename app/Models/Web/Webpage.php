<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageStateEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Models\Helpers\Snapshot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * App\Models\Web\Webpage
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string $url
 * @property int $level
 * @property bool $is_fixed
 * @property WebpageStateEnum $state
 * @property WebpageTypeEnum $type
 * @property WebpagePurposeEnum $purpose
 * @property int|null $parent_id
 * @property int $website_id
 * @property int|null $unpublished_snapshot_id
 * @property int|null $live_snapshot_id
 * @property array $compiled_layout
 * @property string|null $ready_at
 * @property string|null $live_at
 * @property string|null $closed_at
 * @property array $data
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read \App\Models\Web\WebpageStats|null $stats
 * @property-read Snapshot|null $unpublishedSnapshot
 * @property-read \App\Models\Web\Website $website
 * @method static Builder|Webpage newModelQuery()
 * @method static Builder|Webpage newQuery()
 * @method static Builder|Webpage query()
 * @method static Builder|Webpage whereClosedAt($value)
 * @method static Builder|Webpage whereCode($value)
 * @method static Builder|Webpage whereCompiledLayout($value)
 * @method static Builder|Webpage whereCreatedAt($value)
 * @method static Builder|Webpage whereData($value)
 * @method static Builder|Webpage whereDeletedAt($value)
 * @method static Builder|Webpage whereId($value)
 * @method static Builder|Webpage whereIsFixed($value)
 * @method static Builder|Webpage whereLevel($value)
 * @method static Builder|Webpage whereLiveAt($value)
 * @method static Builder|Webpage whereLiveSnapshotId($value)
 * @method static Builder|Webpage whereParentId($value)
 * @method static Builder|Webpage wherePurpose($value)
 * @method static Builder|Webpage whereReadyAt($value)
 * @method static Builder|Webpage whereSettings($value)
 * @method static Builder|Webpage whereSlug($value)
 * @method static Builder|Webpage whereState($value)
 * @method static Builder|Webpage whereType($value)
 * @method static Builder|Webpage whereUnpublishedSnapshotId($value)
 * @method static Builder|Webpage whereUpdatedAt($value)
 * @method static Builder|Webpage whereUrl($value)
 * @method static Builder|Webpage whereWebsiteId($value)
 * @mixin \Eloquent
 */
class Webpage extends Model
{
    use HasSlug;

    protected $casts = [
        'data'             => 'array',
        'settings'         => 'array',
        'compiled_layout' => 'array',
        'type'             => WebpageTypeEnum::class,
        'purpose'          => WebpagePurposeEnum::class,
        'state'            => WebpageStateEnum::class,

    ];

    protected $attributes = [
        'data'             => '{}',
        'settings'         => '{}',
        'compiled_layout' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebpageStats::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'parent');
    }
    public function unpublishedSnapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class, 'unpublished_snapshot_id');
    }



}
