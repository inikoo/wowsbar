<?php

/** @noinspection PhpUnused */

/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Actions\Helpers\Images\GetPictureSources;
use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Helpers\ImgProxy\Image;
use App\Models\Helpers\Deployment;
use App\Models\Helpers\Snapshot;
use App\Models\Market\Shop;
use App\Models\Media\Media;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * App\Models\Web\Website
 *
 * @property int $id
 * @property int $shop_id
 * @property string $type
 * @property string $slug
 * @property string $code
 * @property string $name
 * @property WebsiteStateEnum $state
 * @property bool $status
 * @property string $domain
 * @property array $settings
 * @property array $data
 * @property array $layout
 * @property array $compiled_layout
 * @property int|null $unpublished_header_snapshot_id
 * @property int|null $live_header_snapshot_id
 * @property string|null $published_header_checksum
 * @property bool $header_is_dirty
 * @property int|null $unpublished_footer_snapshot_id
 * @property int|null $live_footer_snapshot_id
 * @property string|null $published_footer_checksum
 * @property bool $footer_is_dirty
 * @property int|null $current_layout_id
 * @property int $organisation_id
 * @property int|null $logo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $launched_at
 * @property string|null $closed_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property int|null $home_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Deployment> $deployments
 * @property-read int|null $deployments_count
 * @property-read \App\Models\Web\Webpage|null $home
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $images
 * @property-read int|null $images_count
 * @property-read Snapshot $liveSnapshot
 * @property-read Media|null $logo
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Shop $shop
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read Snapshot|null $unpublishedFooterSnapshot
 * @property-read Snapshot|null $unpublishedHeaderSnapshot
 * @property-read \App\Models\Web\WebsiteStats|null $webStats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\Webpage> $webpages
 * @property-read int|null $webpages_count
 * @method static Builder|Website newModelQuery()
 * @method static Builder|Website newQuery()
 * @method static Builder|Website onlyTrashed()
 * @method static Builder|Website query()
 * @method static Builder|Website whereClosedAt($value)
 * @method static Builder|Website whereCode($value)
 * @method static Builder|Website whereCompiledLayout($value)
 * @method static Builder|Website whereCreatedAt($value)
 * @method static Builder|Website whereCurrentLayoutId($value)
 * @method static Builder|Website whereData($value)
 * @method static Builder|Website whereDeleteComment($value)
 * @method static Builder|Website whereDeletedAt($value)
 * @method static Builder|Website whereDomain($value)
 * @method static Builder|Website whereFooterIsDirty($value)
 * @method static Builder|Website whereHeaderIsDirty($value)
 * @method static Builder|Website whereHomeId($value)
 * @method static Builder|Website whereId($value)
 * @method static Builder|Website whereLaunchedAt($value)
 * @method static Builder|Website whereLayout($value)
 * @method static Builder|Website whereLiveFooterSnapshotId($value)
 * @method static Builder|Website whereLiveHeaderSnapshotId($value)
 * @method static Builder|Website whereLogoId($value)
 * @method static Builder|Website whereName($value)
 * @method static Builder|Website whereOrganisationId($value)
 * @method static Builder|Website wherePublishedFooterChecksum($value)
 * @method static Builder|Website wherePublishedHeaderChecksum($value)
 * @method static Builder|Website whereSettings($value)
 * @method static Builder|Website whereShopId($value)
 * @method static Builder|Website whereSlug($value)
 * @method static Builder|Website whereState($value)
 * @method static Builder|Website whereStatus($value)
 * @method static Builder|Website whereType($value)
 * @method static Builder|Website whereUnpublishedFooterSnapshotId($value)
 * @method static Builder|Website whereUnpublishedHeaderSnapshotId($value)
 * @method static Builder|Website whereUpdatedAt($value)
 * @method static Builder|Website withTrashed()
 * @method static Builder|Website withoutTrashed()
 * @mixin \Eloquent
 */
class Website extends Model implements Auditable, HasMedia
{
    use HasSlug;
    use SoftDeletes;
    use HasHistory;
    use HasUniversalSearch;
    use InteractsWithMedia;


    protected $casts = [
        'data'               => 'array',
        'settings'           => 'array',
        'layout'             => 'array',
        'compiled_layout'    => 'array',
        'state'              => WebsiteStateEnum::class,
    ];

    protected $attributes = [
        'data'               => '{}',
        'settings'           => '{}',
        'layout'             => '{}',
        'compiled_layout'    => '{}'
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'websites'
        ];
    }
    protected array $auditExclude = [
        'id',
        'slug',
        'home_id',
        'live_header_snapshot_id',
        'live_footer_snapshot_id','home_id',
        'compiled_layout','unpublished_header_snapshot_id',
        'unpublished_footer_snapshot_id',
        'published_header_checksum','published_footer_checksum'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(8)
            ->saveSlugsTo('slug');
    }


    public function webpages(): HasMany
    {
        return $this->hasMany(Webpage::class);
    }

    public function webStats(): HasOne
    {
        return $this->hasOne(WebsiteStats::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function home(): BelongsTo
    {
        return $this->belongsTo(Webpage::class, 'home_id');
    }

    protected function condition(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if ($attributes['state'] == 'live') {
                    return $attributes['status'] ? 'live' : 'maintenance';
                }

                return $attributes['state'];
            }
        );
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_media');
    }

    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'parent');
    }

    public function unpublishedHeaderSnapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class, 'unpublished_header_snapshot_id');
    }

    public function unpublishedFooterSnapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class, 'unpublished_footer_snapshot_id');
    }

    public function liveSnapshot(): BelongsTo
    {
        return $this->belongsTo(Snapshot::class, 'live_snapshot_id');
    }

    public function deployments(): MorphMany
    {
        return $this->morphMany(Deployment::class, 'model');
    }

    public function logo(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'logo_id');
    }

    public function logoImageSources($width = 0, $height = 0)
    {
        if($this->logo) {
            $logoThumbnail = (new Image())->make($this->logo->getImgProxyFilename())->resize($width, $height);
            return GetPictureSources::run($logoThumbnail);
        }
        return null;
    }



}
