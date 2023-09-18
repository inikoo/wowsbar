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
 * @property int $number_prospects
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
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployees($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesGenderFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesGenderMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesGenderOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesStateHired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesStateLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesStateWorking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesTypeEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesTypeTemporalWorker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesTypeVolunteer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberEmployeesTypeWorkExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberJobPositions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganisationCrmStats whereNumberWorkingPlaces($value)
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
