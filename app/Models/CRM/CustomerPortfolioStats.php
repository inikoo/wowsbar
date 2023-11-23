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
 * @property int $number_prospects_state_fail
 * @property int $number_prospects_state_success
 * @property int $number_prospects_gender_male
 * @property int $number_prospects_gender_female
 * @property int $number_prospects_gender_other
 * @property int $number_stock_images
 * @property int $number_stock_images_scope_landscape
 * @property int $number_stock_images_scope_square
 * @property int $number_uploaded_images
 * @property int $number_uploaded_images_scope_landscape
 * @property int $number_uploaded_images_scope_square
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannerSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersNoWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersSnapshotsStateHistoric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersSnapshotsStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersSnapshotsStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersStateLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersStateSwitchOff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersStateUnpublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersTypeLandscape($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberBannersTypeSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberHistoricSnapshots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesBannersCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesBannersInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesBannersNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesBannersNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesDivisionBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesDivisionPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesDivisionProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesDivisionSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesDivisionSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesPpcCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesPpcInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesPpcNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesPpcNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesProspectsCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesProspectsInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesProspectsNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesProspectsNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSeoCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSeoInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSeoNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSeoNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSocialCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSocialInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSocialNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberPortfolioWebsitesSocialNotSure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspectsGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspectsGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspectsGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspectsStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspectsStateFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspectsStateNoContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberProspectsStateSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberStockImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberStockImagesScopeLandscape($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberStockImagesScopeSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberUploadedImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberUploadedImagesScopeLandscape($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereNumberUploadedImagesScopeSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerPortfolioStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomerPortfolioStats extends Model
{
    protected $guarded = [];
}
