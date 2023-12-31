<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 24 Aug 2023 16:16:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToCustomer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolio\BannerStats
 *
 * @property int $id
 * @property int $customer_id
 * @property int $banner_id
 * @property int $number_snapshots
 * @property int $number_snapshots_state_unpublished
 * @property int $number_snapshots_state_live
 * @property int $number_snapshots_state_historic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $number_users
 * @property int $number_clicks
 * @property int $number_views
 * @property-read \App\Models\Portfolio\Banner $banner
 * @property-read \App\Models\CRM\Customer $customer
 * @method static Builder|BannerStats newModelQuery()
 * @method static Builder|BannerStats newQuery()
 * @method static Builder|BannerStats query()
 * @method static Builder|BannerStats whereBannerId($value)
 * @method static Builder|BannerStats whereCreatedAt($value)
 * @method static Builder|BannerStats whereCustomerId($value)
 * @method static Builder|BannerStats whereId($value)
 * @method static Builder|BannerStats whereNumberClicks($value)
 * @method static Builder|BannerStats whereNumberSnapshots($value)
 * @method static Builder|BannerStats whereNumberSnapshotsStateHistoric($value)
 * @method static Builder|BannerStats whereNumberSnapshotsStateLive($value)
 * @method static Builder|BannerStats whereNumberSnapshotsStateUnpublished($value)
 * @method static Builder|BannerStats whereNumberUsers($value)
 * @method static Builder|BannerStats whereNumberViews($value)
 * @method static Builder|BannerStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BannerStats extends Model
{
    use BelongsToCustomer;
    protected $table = 'banner_stats';

    protected $guarded = [];

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }
}
