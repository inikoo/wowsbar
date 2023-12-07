<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Market\ShopCRMStats
 *
 * @property int $id
 * @property int $shop_id
 * @property int $number_customers
 * @property int $number_customers_state_registered
 * @property int $number_customers_state_with_appointment
 * @property int $number_customers_state_contacted
 * @property int $number_customers_state_active
 * @property int $number_customers_state_losing
 * @property int $number_customers_state_lost
 * @property int $number_orders
 * @property int $number_orders_state_creating
 * @property int $number_orders_state_submitted
 * @property int $number_orders_state_handling
 * @property int $number_orders_state_packed
 * @property int $number_orders_state_finalised
 * @property int $number_orders_state_settled
 * @property int $number_customer_websites
 * @property int $number_customer_websites_seo
 * @property int $number_customer_websites_ppc
 * @property int $number_customer_websites_social
 * @property int $number_customer_websites_prospects
 * @property int $number_customer_websites_banners
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $number_prospects
 * @property int $number_prospects_gender_male
 * @property int $number_prospects_gender_female
 * @property int $number_prospects_gender_other
 * @property int $number_customer_users
 * @property int $number_customer_users_status_active
 * @property int $number_customer_users_status_inactive
 * @property int $number_prospects_state_no_contacted
 * @property int $number_prospects_state_contacted
 * @property int $number_prospects_state_fail
 * @property int $number_prospects_state_success
 * @property int $number_prospects_contacted_state_no_applicable
 * @property int $number_prospects_contacted_state_soft_bounced
 * @property int $number_prospects_contacted_state_never_open
 * @property int $number_prospects_contacted_state_open
 * @property int $number_prospects_contacted_state_clicked
 * @property int $number_prospects_fail_status_no_applicable
 * @property int $number_prospects_fail_status_not_interested
 * @property int $number_prospects_fail_status_unsubscribed
 * @property int $number_prospects_fail_status_hard_bounced
 * @property int $number_prospects_fail_status_invalid
 * @property int $number_prospects_success_status_no_applicable
 * @property int $number_prospects_success_status_registered
 * @property int $number_prospects_success_status_invoiced
 * @property int $number_prospects_dont_contact_me
 * @property int $number_prospect_queries
 * @property int $number_customer_queries
 * @property int $number_surveys
 * @property-read \App\Models\Market\Shop $shop
 * @method static Builder|ShopCRMStats newModelQuery()
 * @method static Builder|ShopCRMStats newQuery()
 * @method static Builder|ShopCRMStats query()
 * @method static Builder|ShopCRMStats whereCreatedAt($value)
 * @method static Builder|ShopCRMStats whereId($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerQueries($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerUsers($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerUsersStatusActive($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerUsersStatusInactive($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerWebsites($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerWebsitesBanners($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerWebsitesPpc($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerWebsitesProspects($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerWebsitesSeo($value)
 * @method static Builder|ShopCRMStats whereNumberCustomerWebsitesSocial($value)
 * @method static Builder|ShopCRMStats whereNumberCustomers($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateActive($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateContacted($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateLosing($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateLost($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateRegistered($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateWithAppointment($value)
 * @method static Builder|ShopCRMStats whereNumberOrders($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateCreating($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateFinalised($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateHandling($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStatePacked($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateSettled($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateSubmitted($value)
 * @method static Builder|ShopCRMStats whereNumberProspectQueries($value)
 * @method static Builder|ShopCRMStats whereNumberProspects($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsContactedStateClicked($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsContactedStateNeverOpen($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsContactedStateNoApplicable($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsContactedStateOpen($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsContactedStateSoftBounced($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsDontContactMe($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsFailStatusHardBounced($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsFailStatusInvalid($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsFailStatusNoApplicable($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsFailStatusNotInterested($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsFailStatusUnsubscribed($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsGenderFemale($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsGenderMale($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsGenderOther($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateContacted($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateFail($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateNoContacted($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateSuccess($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsSuccessStatusInvoiced($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsSuccessStatusNoApplicable($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsSuccessStatusRegistered($value)
 * @method static Builder|ShopCRMStats whereNumberSurveys($value)
 * @method static Builder|ShopCRMStats whereShopId($value)
 * @method static Builder|ShopCRMStats whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ShopCRMStats extends Model
{
    protected $table = 'shop_crm_stats';

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
