<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:40:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolio\PortfolioWebsiteStats
 *
 * @property int $id
 * @property int $portfolio_website_id
 * @property int $number_banners
 * @property int $number_historic_snapshots
 * @property int $number_banners_type_landscape
 * @property int $number_banners_type_square
 * @property int $number_banners_state_unpublished
 * @property int $number_banners_state_live
 * @property int $number_banners_state_switch_off
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
 * @property-read \App\Models\Portfolio\PortfolioWebsite $website
 * @method static Builder|PortfolioWebsiteStats newModelQuery()
 * @method static Builder|PortfolioWebsiteStats newQuery()
 * @method static Builder|PortfolioWebsiteStats query()
 * @method static Builder|PortfolioWebsiteStats whereCreatedAt($value)
 * @method static Builder|PortfolioWebsiteStats whereId($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannerSnapshots($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBanners($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersSnapshotsStateHistoric($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersSnapshotsStateLive($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersSnapshotsStateUnpublished($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersStateLive($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersStateRetired($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersStateUnpublished($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersTypeLandscape($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberBannersTypeSquare($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberHistoricSnapshots($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspects($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsGenderFemale($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsGenderMale($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsGenderOther($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsStateBounced($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsStateContacted($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsStateInvoiced($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsStateNoContacted($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsStateNotInterested($value)
 * @method static Builder|PortfolioWebsiteStats whereNumberProspectsStateRegistered($value)
 * @method static Builder|PortfolioWebsiteStats wherePortfolioWebsiteId($value)
 * @method static Builder|PortfolioWebsiteStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PortfolioWebsiteStats extends Model
{
    protected $table = 'portfolio_website_stats';

    protected $guarded = [];

    public function website(): BelongsTo
    {
        return $this->belongsTo(PortfolioWebsite::class);
    }
}
