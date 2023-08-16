<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 12:10:13 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation;

use App\Models\Traits\HasOrganisationUniversalSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\OrganisationStats
 *
 * @property int $id
 * @property int $organisation_id
 * @property int $number_guests
 * @property int $number_guests_status_active
 * @property int $number_guests_status_inactive
 * @property int $number_organisation_users
 * @property int $number_organisation_users_status_active
 * @property int $number_organisation_users_status_inactive
 * @property int $number_organisation_users_type_employee
 * @property int $number_organisation_users_type_guest
 * @property int $number_images
 * @property int $filesize_images
 * @property int $number_attachments
 * @property int $filesize_attachments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Organisation $organisation
 * @method static Builder|OrganisationStats newModelQuery()
 * @method static Builder|OrganisationStats newQuery()
 * @method static Builder|OrganisationStats query()
 * @method static Builder|OrganisationStats whereCreatedAt($value)
 * @method static Builder|OrganisationStats whereFilesizeAttachments($value)
 * @method static Builder|OrganisationStats whereFilesizeImages($value)
 * @method static Builder|OrganisationStats whereId($value)
 * @method static Builder|OrganisationStats whereNumberAttachments($value)
 * @method static Builder|OrganisationStats whereNumberGuests($value)
 * @method static Builder|OrganisationStats whereNumberGuestsStatusActive($value)
 * @method static Builder|OrganisationStats whereNumberGuestsStatusInactive($value)
 * @method static Builder|OrganisationStats whereNumberImages($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsers($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersStatusActive($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersStatusInactive($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersTypeEmployee($value)
 * @method static Builder|OrganisationStats whereNumberOrganisationUsersTypeGuest($value)
 * @method static Builder|OrganisationStats whereOrganisationId($value)
 * @method static Builder|OrganisationStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrganisationStats extends Model
{
    use HasOrganisationUniversalSearch;

    protected $table = 'organisation_stats';

    protected $guarded = [];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
}
