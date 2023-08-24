<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:02:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToTenant;
use App\Enums\Portfolio\Snapshot\SnapshotStateEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\Snapshot
 *
 * @property int $id
 * @property string $slug
 * @property int $tenant_id
 * @property string|null $parent_type
 * @property int|null $parent_id
 * @property SnapshotStateEnum $state
 * @property string|null $published_at
 * @property string|null $published_until
 * @property string $checksum
 * @property array $compiled_layout
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $parent
 * @property-read \App\Models\Portfolio\SnapshotStats|null $stats
 * @property-read \App\Models\Tenancy\Tenant $tenant
 * @method static Builder|Snapshot newModelQuery()
 * @method static Builder|Snapshot newQuery()
 * @method static Builder|Snapshot query()
 * @method static Builder|Snapshot whereChecksum($value)
 * @method static Builder|Snapshot whereComment($value)
 * @method static Builder|Snapshot whereCompiledLayout($value)
 * @method static Builder|Snapshot whereCreatedAt($value)
 * @method static Builder|Snapshot whereId($value)
 * @method static Builder|Snapshot whereParentId($value)
 * @method static Builder|Snapshot whereParentType($value)
 * @method static Builder|Snapshot wherePublishedAt($value)
 * @method static Builder|Snapshot wherePublishedUntil($value)
 * @method static Builder|Snapshot whereSlug($value)
 * @method static Builder|Snapshot whereState($value)
 * @method static Builder|Snapshot whereTenantId($value)
 * @method static Builder|Snapshot whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Snapshot extends Model
{
    use HasSlug;
    use BelongsToTenant;

    protected $casts = [
        'compiled_layout'  => 'array',
        'state'            => SnapshotStateEnum::class
    ];

    protected $attributes = [
        'compiled_layout' => '{}'
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return $this->parent->slug.'-'.now()->isoFormat('YYMMDD');
            })
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function stats(): HasOne
    {
        return $this->hasOne(SnapshotStats::class);
    }

}
