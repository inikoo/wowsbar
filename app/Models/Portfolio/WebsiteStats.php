<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:40:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolio\WebsiteStats
 *
 * @property int $id
 * @property int $website_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Portfolio\Website $website
 * @method static Builder|WebsiteStats newModelQuery()
 * @method static Builder|WebsiteStats newQuery()
 * @method static Builder|WebsiteStats query()
 * @method static Builder|WebsiteStats whereCreatedAt($value)
 * @method static Builder|WebsiteStats whereId($value)
 * @method static Builder|WebsiteStats whereUpdatedAt($value)
 * @method static Builder|WebsiteStats whereWebsiteId($value)
 * @mixin \Eloquent
 */
class WebsiteStats extends Model
{
    protected $table = 'website_stats';

    protected $guarded = [];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
