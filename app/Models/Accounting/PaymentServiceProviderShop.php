<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:30:32 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\Accounting\PaymentServiceProviderShop
 *
 * @property int $id
 * @property int $shop_id
 * @property int $payment_service_provider_id
 * @property int $currency_id
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PaymentServiceProviderShop newModelQuery()
 * @method static Builder|PaymentServiceProviderShop newQuery()
 * @method static Builder|PaymentServiceProviderShop query()
 * @method static Builder|PaymentServiceProviderShop whereCreatedAt($value)
 * @method static Builder|PaymentServiceProviderShop whereCurrencyId($value)
 * @method static Builder|PaymentServiceProviderShop whereData($value)
 * @method static Builder|PaymentServiceProviderShop whereId($value)
 * @method static Builder|PaymentServiceProviderShop wherePaymentServiceProviderId($value)
 * @method static Builder|PaymentServiceProviderShop whereShopId($value)
 * @method static Builder|PaymentServiceProviderShop whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentServiceProviderShop extends Pivot
{
    public $incrementing = true;

    protected $casts = [
        'data'     => 'array',
    ];

    protected $attributes = [
        'data'     => '{}',
    ];

    protected $guarded = [];
}
