<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Web\WebsiteStats
 *
 * @property int $id
 * @property int $website_id
 * @property int $number_webpages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Web\Website $website
 * @method static Builder|WebsiteStats newModelQuery()
 * @method static Builder|WebsiteStats newQuery()
 * @method static Builder|WebsiteStats query()
 * @method static Builder|WebsiteStats whereCreatedAt($value)
 * @method static Builder|WebsiteStats whereId($value)
 * @method static Builder|WebsiteStats whereNumberWebpages($value)
 * @method static Builder|WebsiteStats whereUpdatedAt($value)
 * @method static Builder|WebsiteStats whereWebsiteId($value)
 * @mixin \Eloquent
 */
class WebsiteStats extends Model
{
    use UsesTenantConnection;
    protected $table = 'website_stats';

    protected $guarded = [];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
