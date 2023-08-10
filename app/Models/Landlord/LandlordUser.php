<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 10:15:50 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Landlord;

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
 * App\Models\Landlord\LandlordUser
 *
 * @property int $id
 * @property int $landlord_id
 * @property bool $status
 * @property string $username
 * @property string|null $contact_name
 * @property string $email
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
 * @property-read \App\Models\Landlord\LandlordUserStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|LandlordUser newModelQuery()
 * @method static Builder|LandlordUser newQuery()
 * @method static Builder|LandlordUser permission($permissions)
 * @method static Builder|LandlordUser query()
 * @method static Builder|LandlordUser role($roles, $guard = null)
 * @method static Builder|LandlordUser whereAbout($value)
 * @method static Builder|LandlordUser whereAvatarId($value)
 * @method static Builder|LandlordUser whereContactName($value)
 * @method static Builder|LandlordUser whereCreatedAt($value)
 * @method static Builder|LandlordUser whereData($value)
 * @method static Builder|LandlordUser whereDeletedAt($value)
 * @method static Builder|LandlordUser whereEmail($value)
 * @method static Builder|LandlordUser whereEmailVerifiedAt($value)
 * @method static Builder|LandlordUser whereId($value)
 * @method static Builder|LandlordUser whereLandlordId($value)
 * @method static Builder|LandlordUser whereLanguageId($value)
 * @method static Builder|LandlordUser wherePassword($value)
 * @method static Builder|LandlordUser whereRememberToken($value)
 * @method static Builder|LandlordUser whereSettings($value)
 * @method static Builder|LandlordUser whereStatus($value)
 * @method static Builder|LandlordUser whereUpdatedAt($value)
 * @method static Builder|LandlordUser whereUsername($value)
 * @mixin \Eloquent
 */
class LandlordUser extends Authenticatable implements HasMedia, Auditable
{
    use IsUser;
    use HasFactory;
    use HasApiTokens;
    use HasRoles;
    use InteractsWithMedia;
    use HasHistory;


    protected string $guard_name = 'landlord';

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
        return $this->hasOne(LandlordUserStats::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }

}
