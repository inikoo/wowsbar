<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use App\Http\Resources\Web\WebpageBlocksResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 * @property WebpageTypeEnum $type
 * @property WebpagePurposeEnum $purpose
 * @property int|null $parent_id
 * @property int $website_id
 * @property int|null $main_variant_id
 * @property array $content
 * @property array $blocks
 * @property array $compiled_content
 * @property array $data
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Web\WebpageStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Web\WebpageVariant> $variants
 * @property-read int|null $variants_count
 * @property-read \App\Models\Web\Website $website
 * @method static Builder|Webpage newModelQuery()
 * @method static Builder|Webpage newQuery()
 * @method static Builder|Webpage query()
 * @method static Builder|Webpage whereBlocks($value)
 * @method static Builder|Webpage whereCode($value)
 * @method static Builder|Webpage whereCompiledContent($value)
 * @method static Builder|Webpage whereContent($value)
 * @method static Builder|Webpage whereCreatedAt($value)
 * @method static Builder|Webpage whereData($value)
 * @method static Builder|Webpage whereDeletedAt($value)
 * @method static Builder|Webpage whereId($value)
 * @method static Builder|Webpage whereLevel($value)
 * @method static Builder|Webpage whereMainVariantId($value)
 * @method static Builder|Webpage whereParentId($value)
 * @method static Builder|Webpage wherePurpose($value)
 * @method static Builder|Webpage whereSettings($value)
 * @method static Builder|Webpage whereSlug($value)
 * @method static Builder|Webpage whereType($value)
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
        'blocks'           => 'array',
        'content'          => 'array',
        'compiled_content' => 'array',
        'type'             => WebpageTypeEnum::class,
        'purpose'          => WebpagePurposeEnum::class,

    ];

    protected $attributes = [
        'data'              => '{}',
        'settings'          => '{}',
        'blocks'            => '{}',
        'content'           => '{}',
        'compiled_content'  => '{}',
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

    public function variants(): HasMany
    {
        return $this->hasMany(WebpageVariant::class);
    }

    public function getCompiledContent(): array
    {
        data_set($compiled, 'blocks', WebpageBlocksResource::make($this->blocks)->getArray());

        return $compiled;
    }
}
