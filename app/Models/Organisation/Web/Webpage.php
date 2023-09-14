<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Web;

use App\Enums\Organisation\Web\Webpage\WebpagePurposeEnum;
use App\Enums\Organisation\Web\Webpage\WebpageTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Organisation\Web\Webpage
 *
 * @property int $id
 * @property string $slug
 * @property string $code
 * @property string $url
 * @property WebpagePurposeEnum $purpose
 * @property WebpageTypeEnum $type
 * @property int $website_id
 * @property int|null $main_variant_id
 * @property array $data
 * @property array $settings
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Organisation\Web\WebpageStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation\Web\WebpageVariant> $variants
 * @property-read int|null $variants_count
 * @property-read \App\Models\Organisation\Web\Website $website
 * @method static Builder|Webpage newModelQuery()
 * @method static Builder|Webpage newQuery()
 * @method static Builder|Webpage query()
 * @method static Builder|Webpage whereCode($value)
 * @method static Builder|Webpage whereCreatedAt($value)
 * @method static Builder|Webpage whereData($value)
 * @method static Builder|Webpage whereDeletedAt($value)
 * @method static Builder|Webpage whereId($value)
 * @method static Builder|Webpage whereMainVariantId($value)
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
    use UsesTenantConnection;
    use HasSlug;

    protected $casts = [
        'data'     => 'array',
        'settings' => 'array',
        'type'     => WebpageTypeEnum::class,
        'purpose'  => WebpagePurposeEnum::class,

    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
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
}
