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
 * App\Models\Portfolio\PortfolioWebsiteStats
 *
 * @property int $id
 * @property int $portfolio_website_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Portfolio\PortfolioWebsite|null $website
 * @method static Builder|PortfolioWebsiteStats newModelQuery()
 * @method static Builder|PortfolioWebsiteStats newQuery()
 * @method static Builder|PortfolioWebsiteStats query()
 * @method static Builder|PortfolioWebsiteStats whereCreatedAt($value)
 * @method static Builder|PortfolioWebsiteStats whereId($value)
 * @method static Builder|PortfolioWebsiteStats wherePortfolioWebsiteId($value)
 * @method static Builder|PortfolioWebsiteStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PortfolioWebsiteStats extends Model
{
    protected $table = 'portfolio_website_stats';

    protected $guarded = [];

    public function website(): BelongsTo
    {
        return $this->belongsTo(PortfolioWebsite::class);
    }
}
