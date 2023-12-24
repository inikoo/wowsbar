<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 24 Dec 2023 21:07:17 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolio\PortfolioWebpageStats
 *
 * @property int $id
 * @property int $portfolio_webpage_id
 * @property int $number_of_links
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Portfolio\PortfolioWebpage $portfolioWebpage
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats whereNumberOfLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats wherePortfolioWebpageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpageStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PortfolioWebpageStats extends Model
{
    protected $guarded = [];

    public function portfolioWebpage(): BelongsTo
    {
        return $this->belongsTo(PortfolioWebpage::class);
    }
}
