<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Organisation\Website\WebpageStats
 *
 * @property-read \App\Models\Organisation\Website\Webpage|null $webpage
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebpageStats query()
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
