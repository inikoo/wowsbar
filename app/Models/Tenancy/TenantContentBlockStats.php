<?php

namespace App\Models\Tenancy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tenancy\TenantContentBlockStats
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $number_banners
 * @property int $number_banners_in_process
 * @property int $number_banners_ready
 * @property int $number_banners_live
 * @property int $number_banners_retired
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereNumberBanners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereNumberBannersInProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereNumberBannersLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereNumberBannersReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereNumberBannersRetired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantContentBlockStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TenantContentBlockStats extends Model
{
    use HasFactory;

    protected $guarded = [];
}
