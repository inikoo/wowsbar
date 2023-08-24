<?php

namespace App\Models\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tenancy\TenantPortfolioStats
 *
 * @property int $id
 * @property int $tenant_id
 * @property int $number_banners_no_website
 * @property int $number_banners
 * @property int $number_historic_snapshots
 * @property int $number_banners_state_unpublished
 * @property int $number_banners_state_live
 * @property int $number_banners_state_retired
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|TenantPortfolioStats newModelQuery()
 * @method static Builder|TenantPortfolioStats newQuery()
 * @method static Builder|TenantPortfolioStats query()
 * @method static Builder|TenantPortfolioStats whereCreatedAt($value)
 * @method static Builder|TenantPortfolioStats whereId($value)
 * @method static Builder|TenantPortfolioStats whereNumberBanners($value)
 * @method static Builder|TenantPortfolioStats whereNumberBannersNoWebsite($value)
 * @method static Builder|TenantPortfolioStats whereNumberBannersStateLive($value)
 * @method static Builder|TenantPortfolioStats whereNumberBannersStateRetired($value)
 * @method static Builder|TenantPortfolioStats whereNumberBannersStateUnpublished($value)
 * @method static Builder|TenantPortfolioStats whereNumberHistoricSnapshots($value)
 * @method static Builder|TenantPortfolioStats whereTenantId($value)
 * @method static Builder|TenantPortfolioStats whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TenantPortfolioStats extends Model
{
    use HasFactory;

    protected $guarded = [];
}
