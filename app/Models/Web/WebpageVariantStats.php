<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:20 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\PortfolioWebsite\WebpageVariantStats
 *
 * @property int $id
 * @property int $webpage_variant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Web\WebpageVariant $webpageVariant
 * @method static Builder|WebpageVariantStats newModelQuery()
 * @method static Builder|WebpageVariantStats newQuery()
 * @method static Builder|WebpageVariantStats query()
 * @method static Builder|WebpageVariantStats whereCreatedAt($value)
 * @method static Builder|WebpageVariantStats whereId($value)
 * @method static Builder|WebpageVariantStats whereUpdatedAt($value)
 * @method static Builder|WebpageVariantStats whereWebpageVariantId($value)
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
