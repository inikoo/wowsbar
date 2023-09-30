<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 01 Oct 2023 01:06:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Market\ShopPortfoliosStats
 *
 * @property int $id
 * @property int $shop_id
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
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersNoWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersStateRetired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberHistoricSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberPortfolioWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberSnapshotsStateHistoric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberSnapshotsStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberSnapshotsStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopPortfoliosStats extends Model
{
    protected $guarded = [];
}
