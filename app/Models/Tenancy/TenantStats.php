<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 23 Apr 2023 11:32:21 Malaysia Time, Sanur, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Tenancy\TenantStats
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $number_users
 * @property int $number_users_status_active
 * @property int $number_users_status_inactive
 * @property int $number_websites
 * @property int $number_images
 * @property int $filesize_images
 * @property int $number_attachments
 * @property int $filesize_attachments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenancy\Tenant $tenant
 * @method static Builder|TenantStats newModelQuery()
 * @method static Builder|TenantStats newQuery()
 * @method static Builder|TenantStats query()
 * @method static Builder|TenantStats whereCreatedAt($value)
 * @method static Builder|TenantStats whereFilesizeAttachments($value)
 * @method static Builder|TenantStats whereFilesizeImages($value)
 * @method static Builder|TenantStats whereId($value)
 * @method static Builder|TenantStats whereNumberAttachments($value)
 * @method static Builder|TenantStats whereNumberImages($value)
 * @method static Builder|TenantStats whereNumberUsers($value)
 * @method static Builder|TenantStats whereNumberUsersStatusActive($value)
 * @method static Builder|TenantStats whereNumberUsersStatusInactive($value)
 * @method static Builder|TenantStats whereNumberWebsites($value)
 * @method static Builder|TenantStats whereTenantId($value)
 * @method static Builder|TenantStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TenantStats extends Model
{
    protected $table = 'tenant_stats';

    protected $guarded = [];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
