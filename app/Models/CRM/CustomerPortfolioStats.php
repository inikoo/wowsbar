<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 18 Sep 2023 17:51:02 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\CustomerPortfolioStats
 *
 * @property int $id
 * @property int $customer_id
 * @property int $number_portfolio_websites
 * @property int $number_banners_no_website
 * @property int $number_banners
 * @property int $number_historic_snapshots
 * @property int $number_banners_state_unpublished
 * @property int $number_banners_state_live
 * @property int $number_banners_state_retired
 * @property int $number_snapshots
 * @property int $number_snapshots_state_unpublished
 * @property int $number_snapshots_state_live
 * @property int $number_snapshots_state_historic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersNoWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersStateRetired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberHistoricSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberSnapshotsStateHistoric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberSnapshotsStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberSnapshotsStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerPortfolioStats extends Model
{
    protected $guarded = [];
}
