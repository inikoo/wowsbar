<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:20 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Web;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Web\ContentBlockWebpageVariant
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebpageVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebpageVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebpageVariant query()
 * @mixin \Eloquent
 */
class ContentBlockWebpageVariant extends Pivot
{
    public $incrementing = true;

    protected $guarded = [];
}
