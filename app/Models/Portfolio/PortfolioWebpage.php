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
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;

/**
 * App\Models\Portfolio\PortfolioWebpage
 *
 * @property int $id
 * @property string $slug
 * @property int|null $customer_id
 * @property int|null $portfolio_website_id
 * @property string $title
 * @property string $url
 * @property array $data
 * @property array $layout
 * @property string $status
 * @property string|null $message
 * @property string $source_slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Portfolio\PortfolioWebsite|null $portfolioWebsite
 * @property-read \App\Models\Portfolio\PortfolioWebpageStats|null $stats
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage wherePortfolioWebsiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioWebpage whereSourceSlug($value)
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

    protected $casts = [
        'data'               => 'array',
        'layout'             => 'array',
    ];

    protected $attributes = [
        'layout'             => '{}',
        'data'               => '{}'
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'portfolio'
        ];
    }

    public function portfolioWebsite(): BelongsTo
    {
        return $this->belongsTo(PortfolioWebsite::class, 'portfolio_website_id');
    }

    public function stats(): HasOne
    {
        return $this->hasOne(PortfolioWebpageStats::class);
    }


}
