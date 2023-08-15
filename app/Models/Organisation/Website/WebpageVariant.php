<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Website;

use App\Models\Web\ContentBlock;
use App\Models\Web\ContentBlockWebpageVariant;
use App\Models\Web\Webpage;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\PortfolioWebsite\WebpageVariant
 *
 * @property-read \App\Models\Organisation\Website\WebpageStats|null $stats
 * @method static Builder|WebpageVariant newModelQuery()
 * @method static Builder|WebpageVariant newQuery()
 * @method static Builder|WebpageVariant query()
 * @mixin Eloquent
 */
class WebpageVariant extends Model
{
    use UsesTenantConnection;
    use HasSlug;

    protected $casts = [
        'components' => 'array',
    ];

    protected $attributes = [
        'components' => '{}',
    ];

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('code')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }


    public function webpage(): BelongsTo
    {
        return $this->belongsTo(Webpage::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(WebpageStats::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function contentBlocks(): BelongsToMany
    {
        return $this->belongsToMany(ContentBlock::class)->using(ContentBlockWebpageVariant::class)
            ->withTimestamps()->withPivot(['position']);
    }
}
