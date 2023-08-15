<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 10:04:57 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use App\Models\Traits\HasHistory;
use App\Models\Traits\IsUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Organisation\OrganisationUser
 *
 * @property int $id
 * @property string $username
 * @property int|null $parent_id
 * @property string|null $parent_type
 * @property bool $status
 * @property string|null $contact_name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property string|null $about
 * @property array $data
 * @property array $settings
 * @property int $language_id
 * @property int|null $avatar_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Media\Media|null $avatar
 * @property-read array $es_audits
 * @property-read \App\Models\Assets\Language $language
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Organisation\OrganisationUserStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|OrganisationUser newModelQuery()
 * @method static Builder|OrganisationUser newQuery()
 * @method static Builder|OrganisationUser permission($permissions)
 * @method static Builder|OrganisationUser query()
 * @method static Builder|OrganisationUser role($roles, $guard = null)
 * @method static Builder|OrganisationUser whereAbout($value)
 * @method static Builder|OrganisationUser whereAvatarId($value)
 * @method static Builder|OrganisationUser whereContactName($value)
 * @method static Builder|OrganisationUser whereCreatedAt($value)
 * @method static Builder|OrganisationUser whereData($value)
 * @method static Builder|OrganisationUser whereDeletedAt($value)
 * @method static Builder|OrganisationUser whereEmail($value)
 * @method static Builder|OrganisationUser whereEmailVerifiedAt($value)
 * @method static Builder|OrganisationUser whereId($value)
 * @method static Builder|OrganisationUser whereLanguageId($value)
 * @method static Builder|OrganisationUser whereParentId($value)
 * @method static Builder|OrganisationUser whereParentType($value)
 * @method static Builder|OrganisationUser wherePassword($value)
 * @method static Builder|OrganisationUser whereRememberToken($value)
 * @method static Builder|OrganisationUser whereSettings($value)
 * @method static Builder|OrganisationUser whereStatus($value)
 * @method static Builder|OrganisationUser whereUpdatedAt($value)
 * @method static Builder|OrganisationUser whereUsername($value)
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


    protected string $guard_name = 'org';

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

    protected $guarded = [];


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

}
