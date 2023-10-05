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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Organisation $organisation
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberCustomerWebsites($value)
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
