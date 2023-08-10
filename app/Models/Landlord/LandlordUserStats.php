<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Thu, 10 Aug 2023 10:09:49 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Landlord\LandlordUserStats
 *
 * @property int $id
 * @property int $landlord_user_id
 * @property int $number_logins
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property string|null $last_active_at
 * @property int $number_failed_logins
 * @property string|null $last_failed_login_ip
 * @property string|null $last_failed_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Landlord\LandlordUser $landlordUser
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereLandlordUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereLastActiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereLastFailedLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereLastFailedLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereNumberFailedLogins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereNumberLogins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LandlordUserStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LandlordUserStats extends Model
{
    protected $table = 'landlord_user_stats';

    protected $guarded = [];

    public function landlordUser(): BelongsTo
    {
        return $this->belongsTo(LandlordUser::class);
    }
}
