<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 12 Aug 2023 11:03:06 Malaysia Time, Pantai Lembeng, Bali
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
 * App\Models\Organisation\OrgUser
 *
 * @property int $id
 * @property string $username
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
 * @property-read \App\Models\Organisation\OrgUserStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|OrgUser newModelQuery()
 * @method static Builder|OrgUser newQuery()
 * @method static Builder|OrgUser permission($permissions)
 * @method static Builder|OrgUser query()
 * @method static Builder|OrgUser role($roles, $guard = null)
 * @method static Builder|OrgUser whereAbout($value)
 * @method static Builder|OrgUser whereAvatarId($value)
 * @method static Builder|OrgUser whereContactName($value)
 * @method static Builder|OrgUser whereCreatedAt($value)
 * @method static Builder|OrgUser whereData($value)
 * @method static Builder|OrgUser whereDeletedAt($value)
 * @method static Builder|OrgUser whereEmail($value)
 * @method static Builder|OrgUser whereEmailVerifiedAt($value)
 * @method static Builder|OrgUser whereId($value)
 * @method static Builder|OrgUser whereLanguageId($value)
 * @method static Builder|OrgUser wherePassword($value)
 * @method static Builder|OrgUser whereRememberToken($value)
 * @method static Builder|OrgUser whereSettings($value)
 * @method static Builder|OrgUser whereStatus($value)
 * @method static Builder|OrgUser whereUpdatedAt($value)
 * @method static Builder|OrgUser whereUsername($value)
 * @mixin \Eloquent
 */
class OrgUser extends Authenticatable implements HasMedia, Auditable
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
        return $this->hasOne(OrgUserStats::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }

}
