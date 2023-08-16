<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 16:02:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToTenant;
use App\Http\Resources\Portfolio\ContentBlockComponentResource;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Web\WebBlock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * App\Models\Portfolio\Banner
 *
 * @property int $id
 * @property string $ulid
 * @property int $tenant_id
 * @property string $slug
 * @property string $code
 * @property string $name
 * @property string $state
 * @property string|null $ready_at
 * @property string|null $live_at
 * @property string|null $retired_at
 * @property array $layout
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\Slide> $contentBlockComponents
 * @property-read int|null $content_block_components_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\PortfolioWebsite> $portfolioWebsite
 * @property-read int|null $portfolio_website_count
 * @property-read \App\Models\Tenancy\Tenant $tenant
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static \Database\Factories\Portfolio\BannerFactory factory($count = null, $state = [])
 * @method static Builder|Banner newModelQuery()
 * @method static Builder|Banner newQuery()
 * @method static Builder|Banner onlyTrashed()
 * @method static Builder|Banner query()
 * @method static Builder|Banner whereCode($value)
 * @method static Builder|Banner whereCreatedAt($value)
 * @method static Builder|Banner whereData($value)
 * @method static Builder|Banner whereDeletedAt($value)
 * @method static Builder|Banner whereId($value)
 * @method static Builder|Banner whereLayout($value)
 * @method static Builder|Banner whereLiveAt($value)
 * @method static Builder|Banner whereName($value)
 * @method static Builder|Banner whereReadyAt($value)
 * @method static Builder|Banner whereRetiredAt($value)
 * @method static Builder|Banner whereSlug($value)
 * @method static Builder|Banner whereState($value)
 * @method static Builder|Banner whereTenantId($value)
 * @method static Builder|Banner whereUlid($value)
 * @method static Builder|Banner whereUpdatedAt($value)
 * @method static Builder|Banner withTrashed()
 * @method static Builder|Banner withoutTrashed()
 * @mixin \Eloquent
 */
class Banner extends Model implements HasMedia
{
    use SoftDeletes;
    use HasSlug;
    use HasFactory;
    use BelongsToTenant;
    use HasUniversalSearch;
    use InteractsWithMedia;


    protected $casts = [
        'layout' => 'array',
        'data'   => 'array',
    ];

    protected $attributes = [
        'layout' => '{}',
        'data'   => '{}',
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
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }


    public function contentBlockComponents(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    public function portfolioWebsite(): BelongsToMany
    {
        return $this->belongsToMany(PortfolioWebsite::class)->using(BannerPortfolioWebsite::class)
            ->withTimestamps();
    }

    public function compiledLayout(): array
    {
        $compiledLayout=$this->layout;
        data_set($compiledLayout, 'components', json_decode(ContentBlockComponentResource::collection($this->contentBlockComponents)->toJson(), true));
        return $compiledLayout;

    }
}
