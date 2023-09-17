<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:10:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Assets\Language;
use App\Models\Auth\User;
use App\Models\Media\Media;
use App\Models\Traits\HasUniversalSearch;
use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
 * App\Models\CRM\PublicUser
 *
 * @property int $id
 * @property int|null $customer_id
 * @property bool $status
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
 * @property-read \App\Models\CRM\Customer|null $customer
 * @property-read Language $language
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\CRM\PublicUserStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read User|null $user
 * @method static Builder|PublicUser newModelQuery()
 * @method static Builder|PublicUser newQuery()
 * @method static Builder|PublicUser permission($permissions)
 * @method static Builder|PublicUser query()
 * @method static Builder|PublicUser role($roles, $guard = null)
 * @method static Builder|PublicUser whereAbout($value)
 * @method static Builder|PublicUser whereAvatarId($value)
 * @method static Builder|PublicUser whereContactName($value)
 * @method static Builder|PublicUser whereCreatedAt($value)
 * @method static Builder|PublicUser whereCustomerId($value)
 * @method static Builder|PublicUser whereData($value)
 * @method static Builder|PublicUser whereDeletedAt($value)
 * @method static Builder|PublicUser whereEmail($value)
 * @method static Builder|PublicUser whereEmailVerifiedAt($value)
 * @method static Builder|PublicUser whereId($value)
 * @method static Builder|PublicUser whereLanguageId($value)
 * @method static Builder|PublicUser wherePassword($value)
 * @method static Builder|PublicUser whereRememberToken($value)
 * @method static Builder|PublicUser whereSettings($value)
 * @method static Builder|PublicUser whereStatus($value)
 * @method static Builder|PublicUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PublicUser extends Authenticatable implements HasMedia, CanResetPassword, MustVerifyEmail
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
        return $this->hasOne(PublicUserStats::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }
}
