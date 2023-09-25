<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CRM\Appointment
 *
 * @property int $id
 * @property int $customer_id
 * @property int|null $organisation_user_id
 * @property string $schedule_at
 * @property string|null $description
 * @property string $state
 * @property string $type
 * @property string $event
 * @property string $event_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereEventAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereOrganisationUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereScheduleAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Appointment extends Model
{
    use HasFactory;

    protected $guarded = [];
}
