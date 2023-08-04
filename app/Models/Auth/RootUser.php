<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:32:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Assets\Language;
use App\Models\Media\Media;
use App\Models\Tenancy\Tenant;
use App\Models\Traits\HasUniversalSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Auth\User
 *
 * @property int $id
 * @property int|null $tenant_id
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
 * @property-read Media|null $avatar
 * @property-read Language $language
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Auth\UserStats|null $stats
 * @property-read Tenant|null $tenant
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static Builder|RootUser newModelQuery()
 * @method static Builder|RootUser newQuery()
 * @method static Builder|RootUser permission($permissions)
 * @method static Builder|RootUser query()
 * @method static Builder|RootUser role($roles, $guard = null)
 * @method static Builder|RootUser whereAbout($value)
 * @method static Builder|RootUser whereAvatarId($value)
 * @method static Builder|RootUser whereContactName($value)
 * @method static Builder|RootUser whereCreatedAt($value)
 * @method static Builder|RootUser whereData($value)
 * @method static Builder|RootUser whereDeletedAt($value)
 * @method static Builder|RootUser whereEmail($value)
 * @method static Builder|RootUser whereEmailVerifiedAt($value)
 * @method static Builder|RootUser whereId($value)
 * @method static Builder|RootUser whereLanguageId($value)
 * @method static Builder|RootUser wherePassword($value)
 * @method static Builder|RootUser whereRememberToken($value)
 * @method static Builder|RootUser whereSettings($value)
 * @method static Builder|RootUser whereStatus($value)
 * @method static Builder|RootUser whereTenantId($value)
 * @method static Builder|RootUser whereUpdatedAt($value)
 * @method static Builder|RootUser whereUsername($value)
 * @mixin \Eloquent
 */
class RootUser extends Authenticatable implements HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use InteractsWithMedia;
    use HasUniversalSearch;

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
        return $this->hasOne(UserStats::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }

    public function avatar(): HasOne
    {
        return $this->hasOne(Media::class, 'id', 'avatar_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }


}
