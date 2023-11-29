<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 16:06:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\OrganisationCrmStats
 *
 * @property int $id
 * @property int $organisation_id
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
 * @property int $number_tags
 * @property int $number_surveys
 * @property-read \App\Models\Organisation\Organisation $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerUsersStatusActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerUsersStatusInactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerWebsites($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerWebsitesBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerWebsitesPpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerWebsitesProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerWebsitesSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerWebsitesSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomersStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomersStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomersStateLosing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomersStateLost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomersStateRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomersStateWithAppointment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberOrdersStateCreating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberOrdersStateFinalised($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberOrdersStateHandling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberOrdersStatePacked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberOrdersStateSettled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberOrdersStateSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsContactedStateClicked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsContactedStateNeverOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsContactedStateNoApplicable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsContactedStateOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsContactedStateSoftBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsDontContactMe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsFailStatusHardBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsFailStatusInvalid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsFailStatusNoApplicable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsFailStatusNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsFailStatusUnsubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateNoContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsSuccessStatusInvoiced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsSuccessStatusNoApplicable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsSuccessStatusRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberSurveys($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationCrmStats extends Model
{
    protected $table = 'organisation_crm_stats';

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
