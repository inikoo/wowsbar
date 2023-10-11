<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 16:02:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Portfolio\BannerPortfolioWebsite
 *
 * @property int $id
 * @property string $ulid
 * @property int|null $portfolio_website_id
 * @property int $customer_id
 * @property int $banner_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|BannerPortfolioWebsite newModelQuery()
 * @method static Builder|BannerPortfolioWebsite newQuery()
 * @method static Builder|BannerPortfolioWebsite query()
 * @method static Builder|BannerPortfolioWebsite whereBannerId($value)
 * @method static Builder|BannerPortfolioWebsite whereCreatedAt($value)
 * @method static Builder|BannerPortfolioWebsite whereCustomerId($value)
 * @method static Builder|BannerPortfolioWebsite whereId($value)
 * @method static Builder|BannerPortfolioWebsite wherePortfolioWebsiteId($value)
 * @method static Builder|BannerPortfolioWebsite wherePublishedHash($value)
 * @method static Builder|BannerPortfolioWebsite whereUlid($value)
 * @method static Builder|BannerPortfolioWebsite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BannerPortfolioWebsite extends Pivot
{
    public $incrementing = true;

    protected $guarded = [];
}
