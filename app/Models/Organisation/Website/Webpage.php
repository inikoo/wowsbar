<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Website;

use App\Models\Organisation\Website;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Organisation\Website\Webpage
 *
 * @property-read \App\Models\Organisation\Website\WebpageVariant|null $mainVariant
 * @property-read \App\Models\Organisation\Website\WebpageStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation\Website\WebpageVariant> $variants
 * @property-read int|null $variants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Webpage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webpage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Webpage query()
 * @mixin \Eloquent
 */
class Webpage extends Model
{
    use UsesTenantConnection;
    use HasSlug;

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebpageStats::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function mainVariant(): BelongsTo
    {
        return $this->belongsTo(WebpageVariant::class, 'main_variant_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(WebpageVariant::class);
    }
}
