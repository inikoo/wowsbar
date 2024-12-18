<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:17:53 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

use App\Enums\Organisation\Guest\GuestTypeEnum;
use App\Models\HumanResources\JobPosition;
use App\Models\HumanResources\TimeTracking;
use App\Models\Tasks\Task;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Auth\Guest
 *
 * @property int $id
 * @property string $slug
 * @property string $alias
 * @property bool $status
 * @property GuestTypeEnum $type
 * @property string|null $contact_name
 * @property string|null $company_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $identity_document_type
 * @property string|null $identity_document_number
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $gender
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, JobPosition> $jobPositions
 * @property-read int|null $job_positions_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Auth\OrganisationUser|null $organisationUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read TimeTracking|null $timeTracking
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static \Database\Factories\Auth\GuestFactory factory($count = null, $state = [])
 * @method static Builder|Guest newModelQuery()
 * @method static Builder|Guest newQuery()
 * @method static Builder|Guest onlyTrashed()
 * @method static Builder|Guest query()
 * @method static Builder|Guest whereAlias($value)
 * @method static Builder|Guest whereCompanyName($value)
 * @method static Builder|Guest whereContactName($value)
 * @method static Builder|Guest whereCreatedAt($value)
 * @method static Builder|Guest whereData($value)
 * @method static Builder|Guest whereDateOfBirth($value)
 * @method static Builder|Guest whereDeleteComment($value)
 * @method static Builder|Guest whereDeletedAt($value)
 * @method static Builder|Guest whereEmail($value)
 * @method static Builder|Guest whereGender($value)
 * @method static Builder|Guest whereId($value)
 * @method static Builder|Guest whereIdentityDocumentNumber($value)
 * @method static Builder|Guest whereIdentityDocumentType($value)
 * @method static Builder|Guest wherePhone($value)
 * @method static Builder|Guest whereSlug($value)
 * @method static Builder|Guest whereStatus($value)
 * @method static Builder|Guest whereType($value)
 * @method static Builder|Guest whereUpdatedAt($value)
 * @method static Builder|Guest withTrashed()
 * @method static Builder|Guest withoutTrashed()
 * @mixin \Eloquent
 */
class Guest extends Model implements HasMedia, Auditable
{
    use HasSlug;
    use InteractsWithMedia;
    use SoftDeletes;
    use HasFactory;
    use HasHistory;
    use HasUniversalSearch;


    protected $casts = [
        'data'          => 'array',
        'date_of_birth' => 'datetime:Y-m-d',
        'status'        => 'boolean',
        'type'          => GuestTypeEnum::class
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'sysadmin'
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function () {
                return head(explode(' ', trim($this->contact_name)));
            })
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(16)
            ->doNotGenerateSlugsOnUpdate();
    }

    protected static function booted(): void
    {
        static::updated(function (Guest $guest) {
            if (!$guest->wasRecentlyCreated) {
                if ($guest->wasChanged('status')) {

                    if (!$guest->status) {
                        $guest->organisationUser->update(
                            [
                                'status' => $guest->status
                            ]
                        );
                    }
                }
            }
        });
    }


    public function organisationUser(): MorphOne
    {
        return $this->morphOne(OrganisationUser::class, 'parent');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(256)
                    ->height(256);
            });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function jobPositions(): MorphToMany
    {
        return $this->morphToMany(JobPosition::class, 'job_positionable');
    }

    public function tasks(): MorphToMany
    {
        return $this->morphToMany(Task::class, 'author');
    }

    public function timeTracking(): MorphOne
    {
        return $this->morphOne(TimeTracking::class, 'subject');
    }
}
