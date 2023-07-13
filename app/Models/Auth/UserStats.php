<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 12:32:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Auth\UserStats
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
 * @method static Builder|UserStats newModelQuery()
 * @method static Builder|UserStats newQuery()
 * @method static Builder|UserStats query()
 * @method static Builder|UserStats whereCreatedAt($value)
 * @method static Builder|UserStats whereId($value)
 * @method static Builder|UserStats whereLastActiveAt($value)
 * @method static Builder|UserStats whereLastFailedLoginAt($value)
 * @method static Builder|UserStats whereLastFailedLoginIp($value)
 * @method static Builder|UserStats whereLastLoginAt($value)
 * @method static Builder|UserStats whereLastLoginIp($value)
 * @method static Builder|UserStats whereNumberFailedLogins($value)
 * @method static Builder|UserStats whereNumberLogins($value)
 * @method static Builder|UserStats whereUpdatedAt($value)
 * @method static Builder|UserStats whereUserId($value)
 * @mixin \Eloquent
 */
class UserStats extends Model
{
    protected $table = 'user_stats';

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
