<?php

namespace App\Models\Portfolio;

use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Traits\HasHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\PortfolioSocialAccount
 *
 * @property int $id
 * @property string|null $slug
 * @property string $username
 * @property string $url
 * @property string $provider
 * @property int $number_followers
 * @property int $number_posts
 * @property int $customer_id
 * @property int $shop_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Helpers\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Customer $customer
 * @property-read Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereNumberFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereNumberPosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereUsername($value)
 * @mixin \Eloquent
 */
class PortfolioSocialAccount extends Model implements Auditable
{
    use HasFactory;
    use HasSlug;
    use HasHistory;

    protected $guarded = [];

    protected $casts = [
        'data'        => 'array',
    ];

    protected $attributes = [
        'data' => '{}'
    ];

    protected $auditExclude = ['id', 'slug', 'shop_id', 'data', 'customer_id', 'created_at', 'updated_at'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('username')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(12);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
