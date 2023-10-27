<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\HumanResources;

use App\Enums\HumanResources\Employee\EmployeeStateEnum;
use App\Enums\HumanResources\Employee\EmployeeTypeEnum;
use App\Enums\Miscellaneous\GenderEnum;
use App\Models\Auth\OrganisationUser;
use App\Models\Tasks\Task;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasPhoto;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\HumanResources\Employee
 *
 * @property int $id
 * @property string $slug
 * @property string $alias
 * @property string|null $work_email
 * @property string|null $contact_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property GenderEnum|null $gender
 * @property string|null $worker_number
 * @property string|null $job_title
 * @property string|null $job_position
 * @property int|null $workplace_id
 * @property EmployeeTypeEnum $type
 * @property EmployeeStateEnum $state
 * @property string|null $employment_start_at
 * @property string|null $employment_end_at
 * @property string|null $emergency_contact
 * @property array|null $salary
 * @property array|null $working_hours
 * @property string $week_working_hours
 * @property array $data
 * @property array $job_position_scopes
 * @property array $errors
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HumanResources\JobPosition> $jobPositions
 * @property-read int|null $job_positions_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read OrganisationUser|null $organisationUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read \App\Models\HumanResources\Workplace|null $workplace
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmergencyContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmploymentEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereEmploymentStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereErrors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereIdentityDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereIdentityDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereJobPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereJobPositionScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereWeekWorkingHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereWorkEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereWorkerNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereWorkingHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereWorkplaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee withoutTrashed()
 * @mixin \Eloquent
 */
class Employee extends Model implements HasMedia, Auditable
{
    use HasSlug;
    use SoftDeletes;
    use HasUniversalSearch;
    use HasPhoto;
    use HasFactory;
    use HasHistory;

    protected $casts = [
        'data'                => 'array',
        'errors'              => 'array',
        'salary'              => 'array',
        'working_hours'       => 'array',
        'job_position_scopes' => 'array',
        'date_of_birth'       => 'datetime:Y-m-d',
        'gender'              => GenderEnum::class,
        'state'               => EmployeeStateEnum::class,
        'type'                => EmployeeTypeEnum::class

    ];

    protected $attributes = [
        'data'                => '{}',
        'errors'              => '{}',
        'salary'              => '{}',
        'working_hours'       => '{}',
        'job_position_scopes' => '{}',
    ];


    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'hr'
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('alias')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(16);
    }


    public function jobPositions(): MorphToMany
    {
        return $this->morphToMany(JobPosition::class, 'job_positionable');
    }

    public function organisationUser(): MorphOne
    {
        return $this->morphOne(OrganisationUser::class, 'parent');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class);
    }

    public function tasks(): MorphToMany
    {
        return $this->morphToMany(Task::class, 'author');
    }


}
