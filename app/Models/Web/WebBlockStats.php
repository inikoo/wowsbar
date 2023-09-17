<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PortfolioWebsite\WebBlockStats
 *
 * @property int $id
 * @property int $web_block_id
 * @property int $number_tenants
 * @property int $number_websites
 * @property int $number_webpages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Web\WebBlock $webBlock
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats whereNumberTenants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats whereNumberWebpages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats whereNumberWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockStats whereWebBlockId($value)
 * @mixin \Eloquent
 */
class WebBlockStats extends Model
{
    protected $table = 'web_block_stats';

    protected $guarded = [];

    public function webBlock(): BelongsTo
    {
        return $this->belongsTo(WebBlock::class);
    }
}
