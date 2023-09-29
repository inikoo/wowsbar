<?php

namespace App\Models\Portfolios;

use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Portfolios\CustomerSocialAccount
 *
 * @property int $id
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
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereNumberFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereNumberPosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereUsername($value)
 * @mixin \Eloquent
 */
class CustomerSocialAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
