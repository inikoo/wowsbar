<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 25 Sep 2023 12:26:15 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Portfolios;

use App\Models\CRM\Customer;
use App\Models\Portfolio\Banner;
use App\Models\Portfolio\PortfolioWebsiteStats;
use App\Models\Traits\HasHistory;
use App\Models\Traits\HasUniversalSearch;
use App\Models\Traits\IsWebsitePortfolio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;

/**
 * App\Models\Portfolios\CustomerWebsite
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Banner> $banners
 * @property-read int|null $banners_count
 * @property-read Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organisation\Division> $divisions
 * @property-read int|null $divisions_count
 * @property-read array $es_audits
 * @property-read PortfolioWebsiteStats|null $stats
 * @property-read \App\Models\Search\UniversalSearch|null $universalSearch
 * @method static Builder|CustomerWebsite newModelQuery()
 * @method static Builder|CustomerWebsite newQuery()
 * @method static Builder|CustomerWebsite onlyTrashed()
 * @method static Builder|CustomerWebsite query()
 * @method static Builder|CustomerWebsite whereCreatedAt($value)
 * @method static Builder|CustomerWebsite whereCustomerId($value)
 * @method static Builder|CustomerWebsite whereData($value)
 * @method static Builder|CustomerWebsite whereDeletedAt($value)
 * @method static Builder|CustomerWebsite whereId($value)
 * @method static Builder|CustomerWebsite whereName($value)
 * @method static Builder|CustomerWebsite whereShopId($value)
 * @method static Builder|CustomerWebsite whereSlug($value)
 * @method static Builder|CustomerWebsite whereUpdatedAt($value)
 * @method static Builder|CustomerWebsite whereUrl($value)
 * @method static Builder|CustomerWebsite withTrashed()
 * @method static Builder|CustomerWebsite withoutTrashed()
 * @mixin \Eloquent
 */
class CustomerWebsite extends Model implements Auditable
{
    use HasSlug;
    use SoftDeletes;
    use HasUniversalSearch;
    use HasFactory;
    use HasHistory;
    use IsWebsitePortfolio;

    protected $table='portfolio_websites';

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function stats(): HasOne
    {
        return $this->hasOne(PortfolioWebsiteStats::class, 'portfolio_website_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}
