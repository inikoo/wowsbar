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
 * App\Models\Portfolio\ContentBlock
 *
 * @property int $id
 * @property string $type same as web_block_type.slug
 * @property string $ulid
 * @property int $tenant_id
 * @property int $web_block_type_id
 * @property int $web_block_id
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\ContentBlockComponent> $contentBlockComponents
 * @property-read int|null $content_block_components_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Tenancy\Tenant $tenant
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read WebBlock $webBlock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\PortfolioWebsite> $website
 * @property-read int|null $website_count
 * @method static \Database\Factories\Portfolio\ContentBlockFactory factory($count = null, $state = [])
 * @method static Builder|ContentBlock newModelQuery()
 * @method static Builder|ContentBlock newQuery()
 * @method static Builder|ContentBlock onlyTrashed()
 * @method static Builder|ContentBlock query()
 * @method static Builder|ContentBlock whereCode($value)
 * @method static Builder|ContentBlock whereCreatedAt($value)
 * @method static Builder|ContentBlock whereData($value)
 * @method static Builder|ContentBlock whereDeletedAt($value)
 * @method static Builder|ContentBlock whereId($value)
 * @method static Builder|ContentBlock whereLayout($value)
 * @method static Builder|ContentBlock whereLiveAt($value)
 * @method static Builder|ContentBlock whereName($value)
 * @method static Builder|ContentBlock whereReadyAt($value)
 * @method static Builder|ContentBlock whereRetiredAt($value)
 * @method static Builder|ContentBlock whereSlug($value)
 * @method static Builder|ContentBlock whereState($value)
 * @method static Builder|ContentBlock whereTenantId($value)
 * @method static Builder|ContentBlock whereType($value)
 * @method static Builder|ContentBlock whereUlid($value)
 * @method static Builder|ContentBlock whereUpdatedAt($value)
 * @method static Builder|ContentBlock whereWebBlockId($value)
 * @method static Builder|ContentBlock whereWebBlockTypeId($value)
 * @method static Builder|ContentBlock withTrashed()
 * @method static Builder|ContentBlock withoutTrashed()
 * @mixin \Eloquent
 */
class ContentBlock extends Model implements HasMedia
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


    public function webBlock(): BelongsTo
    {
        return $this->belongsTo(WebBlock::class);
    }

    public function contentBlockComponents(): HasMany
    {
        return $this->hasMany(ContentBlockComponent::class);
    }

    public function website(): BelongsToMany
    {
        return $this->belongsToMany(PortfolioWebsite::class)->using(ContentBlockPortfolioWebsite::class)
            ->withTimestamps();
    }

    public function compiledLayout(): array
    {
        $compiledLayout=$this->layout;
        data_set($compiledLayout, 'components', json_decode(ContentBlockComponentResource::collection($this->contentBlockComponents)->toJson(), true));
        return $compiledLayout;

    }
}
