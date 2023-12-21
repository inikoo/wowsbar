<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 11 Jul 2023 11:29:23 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolio;

use App\Concerns\BelongsToCustomer;
use App\Enums\Portfolio\PortfolioWebsite\PortfolioWebsiteIntegrationEnum;
use App\Models\Leads\Prospect;
use App\Models\SysAdmin\Division;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Traits\IsWebsitePortfolio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;

/**
 * App\Models\Portfolio\PortfolioWebsite
 *
 * @property int $id
 * @property string $slug
 * @property int $shop_id
 * @property int $customer_id
 * @property string $url
 * @property string $name
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $delete_comment
 * @property PortfolioWebsiteIntegrationEnum|null $integration
 * @property array $integration_data
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\Banner> $banners
 * @property-read int|null $banners_count
 * @property-read \App\Models\CRM\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Division> $divisions
 * @property-read int|null $divisions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Portfolio\PortfolioWebpage> $portfolioWebpages
 * @property-read int|null $portfolio_webpages_count
 * @property-read \App\Models\Portfolio\PortfolioWebsiteStats|null $stats
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static Builder|PortfolioWebsite dProspects()
 * @method static \Database\Factories\Portfolio\PortfolioWebsiteFactory factory($count = null, $state = [])
 * @method static Builder|PortfolioWebsite newModelQuery()
 * @method static Builder|PortfolioWebsite newQuery()
 * @method static Builder|PortfolioWebsite onlyTrashed()
 * @method static Builder|PortfolioWebsite query()
 * @method static Builder|PortfolioWebsite whereCreatedAt($value)
 * @method static Builder|PortfolioWebsite whereCustomerId($value)
 * @method static Builder|PortfolioWebsite whereData($value)
 * @method static Builder|PortfolioWebsite whereDeleteComment($value)
 * @method static Builder|PortfolioWebsite whereDeletedAt($value)
 * @method static Builder|PortfolioWebsite whereId($value)
 * @method static Builder|PortfolioWebsite whereIntegration($value)
 * @method static Builder|PortfolioWebsite whereIntegrationData($value)
 * @method static Builder|PortfolioWebsite whereName($value)
 * @method static Builder|PortfolioWebsite whereShopId($value)
 * @method static Builder|PortfolioWebsite whereSlug($value)
 * @method static Builder|PortfolioWebsite whereUpdatedAt($value)
 * @method static Builder|PortfolioWebsite whereUrl($value)
 * @method static Builder|PortfolioWebsite withTrashed()
 * @method static Builder|PortfolioWebsite withoutTrashed()
 * @mixin \Eloquent
 */
class PortfolioWebsite extends Model implements Auditable
{
    use HasSlug;
    use SoftDeletes;
    use HasUniversalSearch;
    use HasFactory;
    use BelongsToCustomer;
    use HasHistory;
    use IsWebsitePortfolio;

    protected $casts = [
        'data'             => 'array',
        'integration_data' => 'array',
        'integration'      => PortfolioWebsiteIntegrationEnum::class
    ];

    protected $attributes = [
        'data'             => '{}',
        'integration_data' => '{}'
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'portfolio'
        ];
    }


    public function stats(): HasOne
    {
        return $this->hasOne(PortfolioWebsiteStats::class);
    }

    public function scopedProspects(): MorphMany
    {
        return $this->morphMany(Prospect::class, 'parent');
    }

    public function portfolioWebpages(): HasMany
    {
        return $this->hasMany(PortfolioWebpage::class);
    }

}
