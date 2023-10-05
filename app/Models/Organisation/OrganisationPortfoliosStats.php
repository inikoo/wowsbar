<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 01 Oct 2023 01:08:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Organisation\OrganisationPortfoliosStats
 *
 * @property int $id
 * @property int $organisation_id
 * @property int $number_portfolio_websites
 * @property int $number_banners_no_website
 * @property int $number_banners
 * @property int $number_historic_snapshots
 * @property int $number_banners_state_unpublished
 * @property int $number_banners_state_live
 * @property int $number_banners_state_retired
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
 * @property int $number_snapshots
 * @property int $number_snapshots_state_unpublished
 * @property int $number_snapshots_state_live
 * @property int $number_snapshots_state_historic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersNoWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersStateRetired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberHistoricSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateInvoiced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateNoContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberSnapshotsStateHistoric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberSnapshotsStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberSnapshotsStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationPortfoliosStats extends Model
{
    protected $guarded = [];
}
