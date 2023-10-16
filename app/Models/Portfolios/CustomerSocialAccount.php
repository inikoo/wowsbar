<?php

namespace App\Models\Portfolios;

use App\Models\CRM\Customer;
use App\Models\Market\Shop;
use App\Models\Traits\IsSocialAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Portfolios\CustomerSocialAccount
 *
 * @property int $id
 * @property string|null $slug
 * @property string $username
 * @property string|null $url
 * @property string $platform
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
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereNumberFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereNumberPosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSocialAccount whereUsername($value)
 * @mixin \Eloquent
 */
class CustomerSocialAccount extends Model implements Auditable
{
    use IsSocialAccount;

    protected $table ='portfolio_social_accounts';

    protected $casts = [
        'data'        => 'array',
    ];

    protected $attributes = [
        'data' => '{}'
    ];

    protected $guarded = [];

    public function generateTags(): array
    {
        return [
            'crm',
        ];
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
