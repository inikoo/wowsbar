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
 * @property int $number_orders
 * @property int $number_orders_state_creating
 * @property int $number_orders_state_submitted
 * @property int $number_orders_state_handling
 * @property int $number_orders_state_packed
 * @property int $number_orders_state_finalised
 * @property int $number_orders_state_settled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Organisation $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateInvoiced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateNoContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberProspectsStateRegistered($value)
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
