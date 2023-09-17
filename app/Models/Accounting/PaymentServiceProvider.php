<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:09:49 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use App\Models\Assets\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Accounting\PaymentServiceProvider
 *
 * @property int $id
 * @property string $type
 * @property string $code
 * @property string $name
 * @property string|null $url
 * @property bool|null $show_marketplace
 * @property array $data
 * @property string|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Accounting\PaymentAccount> $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Country> $countries
 * @property-read int|null $countries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Accounting\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Accounting\PaymentServiceProviderStats|null $stats
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereShowMarketplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentServiceProvider withoutTrashed()
 * @mixin \Eloquent
 */
class PaymentServiceProvider extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, PaymentAccount::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(PaymentAccount::class);
    }

    public function stats(): HasOne
    {
        return $this->hasOne(PaymentServiceProviderStats::class);
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

}
