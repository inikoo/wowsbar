<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 05 Jul 2023 15:31:04 Malaysia Time, Pantai Lembeng, Bali, Id
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Web\WebBlockTypeStats
 *
 * @property int $id
 * @property int $web_block_type_id
 * @property int $number_organisations
 * @property int $number_websites
 * @property int $number_webpages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Web\WebBlockType $webBlockType
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats whereNumberOrganisations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats whereNumberWebpages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats whereNumberWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebBlockTypeStats whereWebBlockTypeId($value)
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
