<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Auth\PublicUserStats
 *
 * @property int $id
 * @property int $user_id
 * @property int $number_logins
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property string|null $last_active_at
 * @property int $number_failed_logins
 * @property string|null $last_failed_login_ip
 * @property string|null $last_failed_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Auth\User $user
 * @method static Builder|PublicUserStats newModelQuery()
 * @method static Builder|PublicUserStats newQuery()
 * @method static Builder|PublicUserStats query()
 * @method static Builder|PublicUserStats whereCreatedAt($value)
 * @method static Builder|PublicUserStats whereId($value)
 * @method static Builder|PublicUserStats whereLastActiveAt($value)
 * @method static Builder|PublicUserStats whereLastFailedLoginAt($value)
 * @method static Builder|PublicUserStats whereLastFailedLoginIp($value)
 * @method static Builder|PublicUserStats whereLastLoginAt($value)
 * @method static Builder|PublicUserStats whereLastLoginIp($value)
 * @method static Builder|PublicUserStats whereNumberFailedLogins($value)
 * @method static Builder|PublicUserStats whereNumberLogins($value)
 * @method static Builder|PublicUserStats whereUpdatedAt($value)
 * @method static Builder|PublicUserStats whereUserId($value)
 * @mixin \Eloquent
 */

class PublicUserStats extends Model
{
    use HasFactory;

    protected $table = 'public_user_stats';

    protected $guarded = [];

    public function publicUser(): BelongsTo
    {
        return $this->belongsTo(PublicUser::class);
    }
}