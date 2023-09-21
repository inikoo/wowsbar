<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Enums\Organisation\Web\Website\WebsiteStateEnum;
use App\Http\Resources\Web\WebsiteHeaderResource;
use App\Models\Market\Shop;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\InteractsWithMedia;

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
 * @property array $header
 * @property array $menu
 * @property array $footer
 * @property array $layout
 * @property array $compiled_structure
 * @property int|null $current_layout_id
 * @property int $organisation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $launched_at
 * @property string|null $closed_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $home_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read array $es_audits
 * @property-read \App\Models\Web\Webpage|null $home
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read Shop $shop
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read \App\Models\Web\WebsiteStats|null $webStats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\Webpage> $webpages
 * @property-read int|null $webpages_count
 * @method static Builder|Website newModelQuery()
 * @method static Builder|Website newQuery()
 * @method static Builder|Website onlyTrashed()
 * @method static Builder|Website query()
 * @method static Builder|Website whereClosedAt($value)
 * @method static Builder|Website whereCode($value)
 * @method static Builder|Website whereCompiledStructure($value)
 * @method static Builder|Website whereCreatedAt($value)
 * @method static Builder|Website whereCurrentLayoutId($value)
 * @method static Builder|Website whereData($value)
 * @method static Builder|Website whereDeletedAt($value)
 * @method static Builder|Website whereDomain($value)
 * @method static Builder|Website whereFooter($value)
 * @method static Builder|Website whereHeader($value)
 * @method static Builder|Website whereHomeId($value)
 * @method static Builder|Website whereId($value)
 * @method static Builder|Website whereLaunchedAt($value)
 * @method static Builder|Website whereLayout($value)
 * @method static Builder|Website whereMenu($value)
 * @method static Builder|Website whereName($value)
 * @method static Builder|Website whereOrganisationId($value)
 * @method static Builder|Website whereSettings($value)
 * @method static Builder|Website whereShopId($value)
 * @method static Builder|Website whereSlug($value)
 * @method static Builder|Website whereState($value)
 * @method static Builder|Website whereStatus($value)
 * @method static Builder|Website whereType($value)
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
        'header'             => 'array',
        'footer'             => 'array',
        'menu'               => 'array',
        'layout'             => 'array',
        'compiled_structure' => 'array',
        'state'              => WebsiteStateEnum::class,
    ];

    protected $attributes = [
        'data'               => '{}',
        'settings'           => '{}',
        'header'             => '{}',
        'footer'             => '{}',
        'menu'               => '{}',
        'layout'             => '{}',
        'compiled_structure' => '{}',
    ];

    protected $guarded = [];

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

    public function getCompiledStructure(): array
    {
        data_set($compiledLayout, 'header', WebsiteHeaderResource::make($this->header)->getArray());

        return $compiledLayout;
    }
}
