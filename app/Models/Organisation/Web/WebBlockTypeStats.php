<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 18:54:39 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Web;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PortfolioWebsite\WebBlockTypeStats
 *
 * @property int $id
 * @property int $web_block_type_id
 * @property int $number_tenants
 * @property int $number_web_blocks
 * @property int $number_websites
 * @property int $number_webpages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Web\WebBlockType $webBlockType
 * @method static Builder|WebBlockTypeStats newModelQuery()
 * @method static Builder|WebBlockTypeStats newQuery()
 * @method static Builder|WebBlockTypeStats query()
 * @method static Builder|WebBlockTypeStats whereCreatedAt($value)
 * @method static Builder|WebBlockTypeStats whereId($value)
 * @method static Builder|WebBlockTypeStats whereNumberTenants($value)
 * @method static Builder|WebBlockTypeStats whereNumberWebBlocks($value)
 * @method static Builder|WebBlockTypeStats whereNumberWebpages($value)
 * @method static Builder|WebBlockTypeStats whereNumberWebsites($value)
 * @method static Builder|WebBlockTypeStats whereUpdatedAt($value)
 * @method static Builder|WebBlockTypeStats whereWebBlockTypeId($value)
 * @mixin \Eloquent
 */
class WebBlockTypeStats extends Model
{
    protected $table = 'web_block_type_stats';

    protected $guarded = [];

    public function webBlockType(): BelongsTo
    {
        return $this->belongsTo(WebBlockType::class);
    }

}
