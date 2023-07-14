<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 13 Jul 2023 16:02:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\Portfolio\ContentBlockWebsite
 *
 * @property int $id
 * @property string $ulid
 * @property int $website_id
 * @property int $tenant_id
 * @property int $content_block_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite whereContentBlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentBlockWebsite whereWebsiteId($value)
 * @mixin \Eloquent
 */
class ContentBlockWebsite extends Pivot
{
    public $incrementing = true;

    protected $guarded = [];
}
