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
 * App\Models\Organisation\OrganisationUserStats
 *
 * @property int $id
 * @property int $organisation_user_id
 * @property int $number_logins
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property string|null $last_active_at
 * @property int $number_failed_logins
 * @property string|null $last_failed_login_ip
 * @property string|null $last_failed_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\OrganisationUser $organisationUser
 * @method static Builder|OrganisationUserStats newModelQuery()
 * @method static Builder|OrganisationUserStats newQuery()
 * @method static Builder|OrganisationUserStats query()
 * @method static Builder|OrganisationUserStats whereCreatedAt($value)
 * @method static Builder|OrganisationUserStats whereId($value)
 * @method static Builder|OrganisationUserStats whereLastActiveAt($value)
 * @method static Builder|OrganisationUserStats whereLastFailedLoginAt($value)
 * @method static Builder|OrganisationUserStats whereLastFailedLoginIp($value)
 * @method static Builder|OrganisationUserStats whereLastLoginAt($value)
 * @method static Builder|OrganisationUserStats whereLastLoginIp($value)
 * @method static Builder|OrganisationUserStats whereNumberFailedLogins($value)
 * @method static Builder|OrganisationUserStats whereNumberLogins($value)
 * @method static Builder|OrganisationUserStats whereOrganisationUserId($value)
 * @method static Builder|OrganisationUserStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationUserStats extends Model
{
    protected $table = 'organisation_user_stats';

    protected $guarded = [];

    public function organisationUser(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class);
    }
}
