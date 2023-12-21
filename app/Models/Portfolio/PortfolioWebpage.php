<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 00:13:18 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Models\Traits\HasHistory;
use App\Models\Traits\IsWebpagePortfolio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;

/**
 * App\Models\Portfolio\PortfolioWebpage
 *
 * @property int $id
 * @property int|null $portfolio_website_id
 * @property string $title
 * @property string $url
 * @property string $layout
 * @property string $status
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Portfolio\PortfolioWebsite|null $portfolioWebsite
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage wherePortfolioWebsiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereUrl($value)
 * @mixin \Eloquent
 */
class PortfolioWebpage extends Model implements Auditable
{
    use HasFactory;
    use HasSlug;
    use HasHistory;
    use IsWebpagePortfolio;

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'portfolio'
        ];
    }

    public function portfolioWebsite(): BelongsTo
    {
        return $this->belongsTo(PortfolioWebsite::class);
    }


}
