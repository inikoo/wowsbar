<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:16:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolio\BannerStats
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $banner_id
 * @property int $number_snapshots_state_unpublished
 * @property int $number_snapshots_state_live
 * @property int $number_snapshots_state_historic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Portfolio\Banner $banner
 * @method static Builder|BannerStats newModelQuery()
 * @method static Builder|BannerStats newQuery()
 * @method static Builder|BannerStats query()
 * @method static Builder|BannerStats whereBannerId($value)
 * @method static Builder|BannerStats whereCreatedAt($value)
 * @method static Builder|BannerStats whereId($value)
 * @method static Builder|BannerStats whereNumberSnapshotsStateHistoric($value)
 * @method static Builder|BannerStats whereNumberSnapshotsStateLive($value)
 * @method static Builder|BannerStats whereNumberSnapshotsStateUnpublished($value)
 * @method static Builder|BannerStats whereTenantId($value)
 * @method static Builder|BannerStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BannerStats extends Model
{
    use BelongsToTenant;
    protected $table = 'banner_stats';

    protected $guarded = [];

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }
}
