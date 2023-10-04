<?php

namespace App\Models\Portfolio;

use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * App\Models\Portfolio\PortfolioSocialAccount
 *
 * @property int $id
 * @property string $slug
 * @property string $username
 * @property string $url
 * @property string $provider
 * @property int $number_followers
 * @property int $number_posts
 * @property int $customer_id
 * @property int $shop_id
 * @property mixed $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioSocialAccount whereUsername($value)
 * @mixin \Eloquent
 */
class PortfolioSocialAccount extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'data'        => 'array',
    ];

    protected $attributes = [
        'data' => '{}'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('username')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(12)
            ->doNotGenerateSlugsOnCreate();
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
