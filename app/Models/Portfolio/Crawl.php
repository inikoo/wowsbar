<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 22 Dec 2023 12:58:01 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Enums\Portfolio\Crawl\CrawlTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolio\Crawl
 *
 * @property int $id
 * @property int $portfolio_website_id
 * @property CrawlTypeEnum $type
 * @property string|null $crawled_at
 * @property int $number_of_crawled_webpages
 * @property int $number_of_new_webpages
 * @property int $number_of_updated_webpages
 * @property int $number_of_deleted_webpages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Portfolio\PortfolioWebsite $portfolioWebsite
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl query()
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereCrawledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereNumberOfCrawledWebpages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereNumberOfDeletedWebpages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereNumberOfNewWebpages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereNumberOfUpdatedWebpages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl wherePortfolioWebsiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Crawl whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Crawl extends Model
{
    protected $casts = [
        'type'               => CrawlTypeEnum::class
    ];

    protected $guarded = [];

    public function portfolioWebsite(): BelongsTo
    {
        return $this->belongsTo(PortfolioWebsite::class);
    }


}
