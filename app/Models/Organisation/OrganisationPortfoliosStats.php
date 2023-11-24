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
 * @property int $number_portfolio_websites_division_seo
 * @property int $number_portfolio_websites_seo_not_sure
 * @property int $number_portfolio_websites_seo_interested
 * @property int $number_portfolio_websites_seo_not_interested
 * @property int $number_portfolio_websites_seo_customer
 * @property int $number_portfolio_websites_division_ppc
 * @property int $number_portfolio_websites_ppc_not_sure
 * @property int $number_portfolio_websites_ppc_interested
 * @property int $number_portfolio_websites_ppc_not_interested
 * @property int $number_portfolio_websites_ppc_customer
 * @property int $number_portfolio_websites_division_social
 * @property int $number_portfolio_websites_social_not_sure
 * @property int $number_portfolio_websites_social_interested
 * @property int $number_portfolio_websites_social_not_interested
 * @property int $number_portfolio_websites_social_customer
 * @property int $number_portfolio_websites_division_prospects
 * @property int $number_portfolio_websites_prospects_not_sure
 * @property int $number_portfolio_websites_prospects_interested
 * @property int $number_portfolio_websites_prospects_not_interested
 * @property int $number_portfolio_websites_prospects_customer
 * @property int $number_portfolio_websites_division_banners
 * @property int $number_portfolio_websites_banners_not_sure
 * @property int $number_portfolio_websites_banners_interested
 * @property int $number_portfolio_websites_banners_not_interested
 * @property int $number_portfolio_websites_banners_customer
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
 * @property int $number_prospects_state_bounced
 * @property int $number_prospects_state_fail
 * @property int $number_prospects_state_success
 * @property int $number_prospects_gender_male
 * @property int $number_prospects_gender_female
 * @property int $number_prospects_gender_other
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannerSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersNoWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersSnapshotsStateHistoric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersSnapshotsStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersSnapshotsStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersStateSwitchOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersTypeLandscape($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberBannersTypeSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberHistoricSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesBannersCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesBannersInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesBannersNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesBannersNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesDivisionBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesDivisionPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesDivisionProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesDivisionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesDivisionSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesPpcCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesPpcInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesPpcNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesPpcNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesProspectsCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesProspectsInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesProspectsNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesProspectsNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSeoCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSeoInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSeoNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSeoNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSocialCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSocialInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSocialNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberPortfolioWebsitesSocialNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateNoContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereNumberProspectsStateSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationPortfoliosStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationPortfoliosStats extends Model
{
    protected $guarded = [];
}
