<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:29:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToCustomer;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use App\Models\Announcement;
use App\Models\Helpers\Deployment;
use App\Models\Helpers\Snapshot;
use App\Models\Leads\Prospect;
use App\Models\Media\Media;
use App\Models\SysAdmin\Division;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Traits\IsWebsitePortfolio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

/**
 * App\Models\Portfolio\PortfolioWebsite
 *
 * @property int $id
 * @property string $slug
 * @property int $shop_id
 * @property int $customer_id
 * @property string $url
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property PortfolioWebsiteIntegrationEnum $integration
 * @property array|null $integration_data
 * @property string $state
 * @property bool $status
 * @property mixed|null $settings
 * @property mixed|null $layout
 * @property array|null $compiled_layout
 * @property int|null $unpublished_header_snapshot_id
 * @property int|null $live_header_snapshot_id
 * @property string|null $published_header_checksum
 * @property bool $header_is_dirty
 * @property int|null $unpublished_footer_snapshot_id
 * @property int|null $live_footer_snapshot_id
 * @property string|null $published_footer_checksum
 * @property bool $footer_is_dirty
 * @property int|null $current_layout_id
 * @property int|null $logo_id
 * @property string|null $launched_at
 * @property string|null $closed_at
 * @property bool $footer_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Announcement> $announcements
 * @property-read int|null $announcements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\Banner> $banners
 * @property-read int|null $banners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\Crawl> $crawlers
 * @property-read int|null $crawlers_count
 * @property-read \App\Models\CRM\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Deployment> $deployments
 * @property-read int|null $deployments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Division> $divisions
 * @property-read int|null $divisions_count
 * @property-read Media|null $image
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $images
 * @property-read int|null $images_count
 * @property-read Snapshot|null $liveSnapshot
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\PortfolioWebpage> $portfolioWebpages
 * @property-read int|null $portfolio_webpages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Snapshot> $snapshots
 * @property-read int|null $snapshots_count
 * @property-read \App\Models\Portfolio\PortfolioWebsiteStats|null $stats
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read Snapshot|null $unpublishedFooterSnapshot
 * @property-read Snapshot|null $unpublishedHeaderSnapshot
 * @method static Builder|PortfolioWebsite dProspects()
 * @method static \Database\Factories\Portfolio\PortfolioWebsiteFactory factory($count = null, $state = [])
 * @method static Builder|PortfolioWebsite newModelQuery()
 * @method static Builder|PortfolioWebsite newQuery()
 * @method static Builder|PortfolioWebsite onlyTrashed()
 * @method static Builder|PortfolioWebsite query()
 * @method static Builder|PortfolioWebsite whereClosedAt($value)
 * @method static Builder|PortfolioWebsite whereCompiledLayout($value)
 * @method static Builder|PortfolioWebsite whereCreatedAt($value)
 * @method static Builder|PortfolioWebsite whereCurrentLayoutId($value)
 * @method static Builder|PortfolioWebsite whereCustomerId($value)
 * @method static Builder|PortfolioWebsite whereData($value)
 * @method static Builder|PortfolioWebsite whereDeleteComment($value)
 * @method static Builder|PortfolioWebsite whereDeletedAt($value)
 * @method static Builder|PortfolioWebsite whereFooterIsDirty($value)
 * @method static Builder|PortfolioWebsite whereFooterStatus($value)
 * @method static Builder|PortfolioWebsite whereHeaderIsDirty($value)
 * @method static Builder|PortfolioWebsite whereId($value)
 * @method static Builder|PortfolioWebsite whereIntegration($value)
 * @method static Builder|PortfolioWebsite whereIntegrationData($value)
 * @method static Builder|PortfolioWebsite whereLaunchedAt($value)
 * @method static Builder|PortfolioWebsite whereLayout($value)
 * @method static Builder|PortfolioWebsite whereLiveFooterSnapshotId($value)
 * @method static Builder|PortfolioWebsite whereLiveHeaderSnapshotId($value)
 * @method static Builder|PortfolioWebsite whereLogoId($value)
 * @method static Builder|PortfolioWebsite whereName($value)
 * @method static Builder|PortfolioWebsite wherePublishedFooterChecksum($value)
 * @method static Builder|PortfolioWebsite wherePublishedHeaderChecksum($value)
 * @method static Builder|PortfolioWebsite whereSettings($value)
 * @method static Builder|PortfolioWebsite whereShopId($value)
 * @method static Builder|PortfolioWebsite whereSlug($value)
 * @method static Builder|PortfolioWebsite whereState($value)
 * @method static Builder|PortfolioWebsite whereStatus($value)
 * @method static Builder|PortfolioWebsite whereUnpublishedFooterSnapshotId($value)
 * @method static Builder|PortfolioWebsite whereUnpublishedHeaderSnapshotId($value)
 * @method static Builder|PortfolioWebsite whereUpdatedAt($value)
 * @method static Builder|PortfolioWebsite whereUrl($value)
 * @method static Builder|PortfolioWebsite withTrashed()
 * @method static Builder|PortfolioWebsite withoutTrashed()
 * @mixin \Eloquent
 */
class PortfolioWebsite extends Model implements Auditable, HasMedia
{
    use HasSlug;
    use SoftDeletes;
    use HasUniversalSearch;
    use HasFactory;
    use BelongsToCustomer;
    use HasHistory;
    use IsWebsitePortfolio;
    use InteractsWithMedia;

    protected $casts = [
        'data'             => 'array',
        'integration_data' => 'array',
        'compiled_layout'  => 'array',
        'integration'      => PortfolioWebsiteIntegrationEnum::class
    ];

    protected $attributes = [
        'data'             => '{}',
        'integration_data' => '{}',
        'footer_status'    => false
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'portfolio'
        ];
    }


    public function stats(): HasOne
    {
        return $this->hasOne(PortfolioWebsiteStats::class);
    }

    public function snapshots(): MorphMany
    {
        return $this->morphMany(Snapshot::class, 'parent');
    }

    public function crawlers(): HasMany
    {
        return $this->hasMany(Crawl::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function scopedProspects(): MorphMany
    {
        return $this->morphMany(Prospect::class, 'parent');
    }

    public function portfolioWebpages(): HasMany
    {
        return $this->hasMany(PortfolioWebpage::class);
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

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_media');
    }
}
