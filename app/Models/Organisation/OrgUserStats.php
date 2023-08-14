<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\OrgUserStats
 *
 * @property int $id
 * @property int $org_user_id
 * @property int $number_logins
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property string|null $last_active_at
 * @property int $number_failed_logins
 * @property string|null $last_failed_login_ip
 * @property string|null $last_failed_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\OrgUser $orgUser
 * @method static Builder|OrgUserStats newModelQuery()
 * @method static Builder|OrgUserStats newQuery()
 * @method static Builder|OrgUserStats query()
 * @method static Builder|OrgUserStats whereCreatedAt($value)
 * @method static Builder|OrgUserStats whereId($value)
 * @method static Builder|OrgUserStats whereLastActiveAt($value)
 * @method static Builder|OrgUserStats whereLastFailedLoginAt($value)
 * @method static Builder|OrgUserStats whereLastFailedLoginIp($value)
 * @method static Builder|OrgUserStats whereLastLoginAt($value)
 * @method static Builder|OrgUserStats whereLastLoginIp($value)
 * @method static Builder|OrgUserStats whereNumberFailedLogins($value)
 * @method static Builder|OrgUserStats whereNumberLogins($value)
 * @method static Builder|OrgUserStats whereOrgUserId($value)
 * @method static Builder|OrgUserStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrgUserStats extends Model
{
    protected $table = 'org_user_stats';

    protected $guarded = [];

    public function orgUser(): BelongsTo
    {
        return $this->belongsTo(OrgUser::class);
    }
}
