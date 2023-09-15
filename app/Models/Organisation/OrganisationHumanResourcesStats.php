<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 15 Sep 2023 16:06:07 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use App\Models\Traits\HasOrganisationUniversalSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\OrganisationHumanResourcesStats
 *
 * @property int $id
 * @property int $organisation_id
 * @property int $number_employees
 * @property int $number_employees_state_hired
 * @property int $number_employees_state_working
 * @property int $number_employees_state_left
 * @property int $number_employees_type_employee
 * @property int $number_employees_type_volunteer
 * @property int $number_employees_type_temporal_worker
 * @property int $number_employees_type_work_experience
 * @property int $number_employees_gender_male
 * @property int $number_employees_gender_female
 * @property int $number_employees_gender_other
 * @property int $number_job_positions
 * @property int $number_working_places
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Organisation $organisation
 * @property-read \App\Models\Search\OrganisationUniversalSearch|null $universalSearch
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesStateHired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesStateLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesStateWorking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesTypeEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesTypeTemporalWorker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesTypeVolunteer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberEmployeesTypeWorkExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberJobPositions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereNumberWorkingPlaces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereOrganisationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationHumanResourcesStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationHumanResourcesStats extends Model
{
    use HasOrganisationUniversalSearch;

    protected $table = 'organisation_human_resources_stats';

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
