<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 15 Nov 2023 13:13:54 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helpers\TagCrmStats
 *
 * @property int $id
 * @property int $tag_id
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
 * @property int $number_prospects_bounce_status_hard_bounce
 * @property int $number_prospects_bounce_status_soft_bounce
 * @property int $number_prospects_bounce_status_ok
 * @property int $number_prospects_outcome_status_hard_fail
 * @property int $number_prospects_outcome_status_soft_fail
 * @property int $number_prospects_outcome_status_waiting
 * @property int $number_prospects_outcome_status_soft_success
 * @property int $number_prospects_outcome_status_hard_success
 * @property int $number_prospects_contacted
 * @property int $number_prospects_not_contacted
 * @property int $number_prospects_dont_contact_me
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberCustomers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberCustomersStateActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberCustomersStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberCustomersStateLosing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberCustomersStateLost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberCustomersStateRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberCustomersStateWithAppointment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspects($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsBounceStatusHardBounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsBounceStatusOk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsBounceStatusSoftBounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsDontContactMe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsNotContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsOutcomeStatusHardFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsOutcomeStatusHardSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsOutcomeStatusSoftFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsOutcomeStatusSoftSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsOutcomeStatusWaiting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsStateBounced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsStateContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsStateInvoiced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsStateNoContacted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsStateNotInterested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereNumberProspectsStateRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TagCrmStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TagCrmStats extends Model
{
    protected $table = 'tag_crm_stats';

    protected $guarded = [];
}
