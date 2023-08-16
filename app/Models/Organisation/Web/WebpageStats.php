<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Organisation\Website\WebpageStats
 *
 * @property int $id
 * @property int $webpage_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Web\Webpage $webpage
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats whereWebpageId($value)
 * @mixin \Eloquent
 */
class WebpageStats extends Model
{
    use UsesTenantConnection;

    protected $table = 'webpage_stats';

    protected $guarded = [];

    public function webpage(): BelongsTo
    {
        return $this->belongsTo(Webpage::class);
    }
}
