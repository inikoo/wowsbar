<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 16:02:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Actions\Utils\Abbreviate;
use App\Concerns\BelongsToCustomer;
use App\Enums\Portfolio\Banner\BannerStateEnum;
use App\Models\Helpers\Deployment;
use App\Models\Helpers\Snapshot;
use App\Models\Media\Media;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\Banner
 *
 * @property int $id
 * @property string $ulid
 * @property string $type
 * @property int $customer_id
 * @property int|null $portfolio_website_id
 * @property string $slug
 * @property string $name
 * @property BannerStateEnum $state
 * @property int|null $unpublished_snapshot_id
 * @property int|null $live_snapshot_id
 * @property string $date
 * @property string|null $live_at
 * @property string|null $switch_off_at
 * @property array $compiled_layout
 * @property array $data
 * @property int|null $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\CRM\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Deployment> $deployments
 * @property-read int|null $deployments_count
 * @property-read Media|null $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $images
 * @property-read int|null $images_count
 * @property-read Snapshot|null $liveSnapshot
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\PortfolioWebsite> $portfolioWebsites
 * @property-read int|null $portfolio_websites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read \App\Models\Portfolio\BannerStats|null $stats
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read Snapshot|null $unpublishedSnapshot
 * @method static \Database\Factories\Portfolio\BannerFactory factory($count = null, $state = [])
 * @method static Builder|Banner newModelQuery()
 * @method static Builder|Banner newQuery()
 * @method static Builder|Banner onlyTrashed()
 * @method static Builder|Banner query()
 * @method static Builder|Banner whereCompiledLayout($value)
 * @method static Builder|Banner whereCreatedAt($value)
 * @method static Builder|Banner whereCustomerId($value)
 * @method static Builder|Banner whereData($value)
 * @method static Builder|Banner whereDate($value)
 * @method static Builder|Banner whereDeleteComment($value)
 * @method static Builder|Banner whereDeletedAt($value)
 * @method static Builder|Banner whereId($value)
 * @method static Builder|Banner whereImageId($value)
 * @method static Builder|Banner whereLiveAt($value)
 * @method static Builder|Banner whereLiveSnapshotId($value)
 * @method static Builder|Banner whereName($value)
 * @method static Builder|Banner wherePortfolioWebsiteId($value)
 * @method static Builder|Banner whereSlug($value)
 * @method static Builder|Banner whereState($value)
 * @method static Builder|Banner whereSwitchOffAt($value)
 * @method static Builder|Banner whereType($value)
 * @method static Builder|Banner whereUlid($value)
 * @method static Builder|Banner whereUnpublishedSnapshotId($value)
 * @method static Builder|Banner whereUpdatedAt($value)
 * @method static Builder|Banner withTrashed()
 * @method static Builder|Banner withoutTrashed()
 * @mixin \Eloquent
 */
class Banner extends Model implements HasMedia, Auditable
{
    use SoftDeletes;
    use HasSlug;
    use HasFactory;
    use BelongsToCustomer;
    use HasUniversalSearch;
    use InteractsWithMedia;
    use HasHistory;

    protected $dateFormat = 'Y-m-d H:i:s P';

    protected $casts = [
        'compiled_layout' => 'array',
        'data'            => 'array',
        'state'           => BannerStateEnum::class
    ];

    protected $attributes = [
        'compiled_layout' => '{}',
        'data'            => '{}',
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'portfolio','banners'
        ];
    }

    protected array $auditExclude = [
        'compiled_layout','unpublished_snapshot_id'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return Abbreviate::run(string:$this->name);
            })
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(16);
    }

    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'parent');
    }

    public function unpublishedSnapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class, 'unpublished_snapshot_id');
    }

    public function liveSnapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class, 'live_snapshot_id');
    }

    public function portfolioWebsites(): BelongsToMany
    {
        return $this->belongsToMany(PortfolioWebsite::class)->using(BannerPortfolioWebsite::class)
            ->withTimestamps();
    }

    public function stats(): HasOne
    {
        return $this->hasOne(BannerStats::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_media');
    }

    public function deployments(): MorphMany
    {
        return $this->morphMany(Deployment::class, 'model');
    }

}
