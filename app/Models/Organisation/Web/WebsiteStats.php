<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Organisation\Website\WebsiteStats
 *
 * @property int $id
 * @property int $website_id
 * @property int $number_webpages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Web\Website $website
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats whereNumberWebpages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebsiteStats whereWebsiteId($value)
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
