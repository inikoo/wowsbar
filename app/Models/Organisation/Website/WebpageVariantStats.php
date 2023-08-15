<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Website;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\PortfolioWebsite\WebpageVariantStats
 *
 * @property-read \App\Models\Organisation\Website\WebpageVariant|null $webpageVariant
 * @method static Builder|WebpageVariantStats newModelQuery()
 * @method static Builder|WebpageVariantStats newQuery()
 * @method static Builder|WebpageVariantStats query()
 * @mixin Eloquent
 */
class WebpageVariantStats extends Model
{
    use UsesTenantConnection;

    protected $table = 'webpage_variant_stats';

    protected $guarded = [];

    public function webpageVariant(): BelongsTo
    {
        return $this->belongsTo(WebpageVariant::class);
    }
}
