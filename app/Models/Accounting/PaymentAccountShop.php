<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:09:49 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * App\Models\Accounting\PaymentAccountShop
 *
 * @property int $id
 * @property int $shop_id
 * @property int $payment_account_id
 * @property int $currency_id
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PaymentAccountShop newModelQuery()
 * @method static Builder|PaymentAccountShop newQuery()
 * @method static Builder|PaymentAccountShop query()
 * @method static Builder|PaymentAccountShop whereCreatedAt($value)
 * @method static Builder|PaymentAccountShop whereCurrencyId($value)
 * @method static Builder|PaymentAccountShop whereData($value)
 * @method static Builder|PaymentAccountShop whereId($value)
 * @method static Builder|PaymentAccountShop wherePaymentAccountId($value)
 * @method static Builder|PaymentAccountShop whereShopId($value)
 * @method static Builder|PaymentAccountShop whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentAccountShop extends Pivot
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
