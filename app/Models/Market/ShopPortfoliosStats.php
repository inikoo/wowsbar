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
 * @property int $number_banners_type_landscape
 * @property int $number_banners_type_square
 * @property int $number_banners_state_unpublished
 * @property int $number_banners_state_live
 * @property int $number_banners_state_retired
 * @property int $number_banner_snapshots
 * @property int $number_banners_snapshots_state_unpublished
 * @property int $number_banners_snapshots_state_live
 * @property int $number_banners_snapshots_state_historic
 * @property int $number_prospects
 * @property int $number_prospects_state_no_contacted
 * @property int $number_prospects_state_contacted
 * @property int $number_prospects_state_not_interested
 * @property int $number_prospects_state_registered
 * @property int $number_prospects_state_invoiced
 * @property int $number_prospects_state_bounced
 * @property int $number_prospects_gender_male
 * @property int $number_prospects_gender_female
 * @property int $number_prospects_gender_other
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannerSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersNoWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersSnapshotsStateHistoric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersSnapshotsStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersSnapshotsStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersStateRetired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersTypeLandscape($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberBannersTypeSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberHistoricSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberPortfolioWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsStateBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsStateInvoiced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsStateNoContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsStateNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereNumberProspectsStateRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShopPortfoliosStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopPortfoliosStats extends Model
{
    protected $guarded = [];
}
