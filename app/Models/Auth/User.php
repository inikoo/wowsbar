<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:32:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\BelongsToCustomer;
use App\Models\Assets\Language;
use App\Models\CRM\Customer;
use App\Models\Media\Media;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Traits\IsUser;
use App\Models\Web\Website;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Auth\User
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int $website_id
 * @property string|null $username
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
 * @property string $ulid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Media|null $avatar
 * @property-read Customer|null $customer
 * @property-read array $es_audits
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @property-read Website $website
 * @method static \Database\Factories\Auth\UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null)
 * @method static Builder|User whereAbout($value)
 * @method static Builder|User whereAvatarId($value)
 * @method static Builder|User whereContactName($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereCustomerId($value)
 * @method static Builder|User whereData($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLanguageId($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereSettings($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUlid($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User whereWebsiteId($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia, Auditable
{
    use IsUser;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use InteractsWithMedia;
    use HasUniversalSearch;
    use HasHistory;


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

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
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

}
