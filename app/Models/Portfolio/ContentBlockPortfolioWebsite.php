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
 * App\Models\Portfolio\ContentBlockPortfolioWebsite
 *
 * @property int $id
 * @property string $ulid
 * @property int|null $portfolio_website_id
 * @property int $tenant_id
 * @property int $content_block_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|ContentBlockPortfolioWebsite newModelQuery()
 * @method static Builder|ContentBlockPortfolioWebsite newQuery()
 * @method static Builder|ContentBlockPortfolioWebsite query()
 * @method static Builder|ContentBlockPortfolioWebsite whereContentBlockId($value)
 * @method static Builder|ContentBlockPortfolioWebsite whereCreatedAt($value)
 * @method static Builder|ContentBlockPortfolioWebsite whereId($value)
 * @method static Builder|ContentBlockPortfolioWebsite wherePortfolioWebsiteId($value)
 * @method static Builder|ContentBlockPortfolioWebsite whereTenantId($value)
 * @method static Builder|ContentBlockPortfolioWebsite whereUlid($value)
 * @method static Builder|ContentBlockPortfolioWebsite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContentBlockPortfolioWebsite extends Pivot
{
    public $incrementing = true;

    protected $guarded = [];
}
