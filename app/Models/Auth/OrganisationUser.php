<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:18:10 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

use App\Models\Helpers\Upload;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasRoles;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Traits\IsUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Auth\OrganisationUser
 *
 * @property int $id
 * @property string $slug
 * @property string|null $parent_type
 * @property int|null $parent_id
 * @property string $username
 * @property string|null $email
 * @property bool $reset_password
 * @property bool $status
 * @property string|null $contact_name
 * @property mixed $password
 * @property string|null $remember_token
 * @property string|null $about
 * @property array $data
 * @property array $settings
 * @property int $language_id
 * @property int|null $avatar_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Media\Media|null $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Upload> $excelUploads
 * @property-read int|null $excel_uploads_count
 * @property-read \App\Models\Assets\Language $language
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Auth\OrganisationUserStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static \Database\Factories\Auth\OrganisationUserFactory factory($count = null, $state = [])
 * @method static Builder|OrganisationUser newModelQuery()
 * @method static Builder|OrganisationUser newQuery()
 * @method static Builder|OrganisationUser onlyTrashed()
 * @method static Builder|OrganisationUser permission($permissions)
 * @method static Builder|OrganisationUser query()
 * @method static Builder|OrganisationUser role($roles, $guard = null)
 * @method static Builder|OrganisationUser whereAbout($value)
 * @method static Builder|OrganisationUser whereAvatarId($value)
 * @method static Builder|OrganisationUser whereContactName($value)
 * @method static Builder|OrganisationUser whereCreatedAt($value)
 * @method static Builder|OrganisationUser whereData($value)
 * @method static Builder|OrganisationUser whereDeleteComment($value)
 * @method static Builder|OrganisationUser whereDeletedAt($value)
 * @method static Builder|OrganisationUser whereEmail($value)
 * @method static Builder|OrganisationUser whereId($value)
 * @method static Builder|OrganisationUser whereLanguageId($value)
 * @method static Builder|OrganisationUser whereParentId($value)
 * @method static Builder|OrganisationUser whereParentType($value)
 * @method static Builder|OrganisationUser wherePassword($value)
 * @method static Builder|OrganisationUser whereRememberToken($value)
 * @method static Builder|OrganisationUser whereResetPassword($value)
 * @method static Builder|OrganisationUser whereSettings($value)
 * @method static Builder|OrganisationUser whereSlug($value)
 * @method static Builder|OrganisationUser whereStatus($value)
 * @method static Builder|OrganisationUser whereUpdatedAt($value)
 * @method static Builder|OrganisationUser whereUsername($value)
 * @method static Builder|OrganisationUser withTrashed()
 * @method static Builder|OrganisationUser withoutTrashed()
 * @mixin \Eloquent
 */
class OrganisationUser extends Authenticatable implements HasMedia, Auditable
{
    use IsUser;
    use HasFactory;
    use HasApiTokens;
    use HasRoles;
    use InteractsWithMedia;
    use HasHistory;
    use HasUniversalSearch;
    use HasSlug;
    use SoftDeletes;


    protected string $guard_name = 'org';

    public function guardName(): string
    {
        return 'org';
    }

    protected $casts = [
        'data'              => 'array',
        'settings'          => 'array',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    protected $attributes = [
        'data'     => '{}',
        'settings' => '{}',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('username')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(8);
    }

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
           'sysadmin'
        ];
    }

    protected array $auditExclude = [
        'id',
        'avatar_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function stats(): HasOne
    {
        return $this->hasOne(OrganisationUserStats::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }

    public function parent(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function excelUploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

}
