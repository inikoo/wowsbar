<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Tue, 20 Jun 2023 20:35:59 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\OMS;

use App\Enums\OMS\Transaction\TransactionStateEnum;
use App\Enums\OMS\Transaction\TransactionStatusEnum;
use App\Enums\OMS\Transaction\TransactionTypeEnum;
use App\Models\CRM\Customer;
use App\Models\Organisation\Market\Shop;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Organisation\OMS\Transaction
 *
 * @property TransactionStateEnum $state
 * @property TransactionStatusEnum $status
 * @property TransactionTypeEnum $type
 * @property-read Customer $customer
 * @property-read Model|\Eloquent $item
 * @property-read \App\Models\Organisation\OMS\Order|null $order
 * @property-write mixed $quantity
 * @property-read Shop $shop
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction onlyTrashed()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction withTrashed()
 * @method static Builder|Transaction withoutTrashed()
 * @mixin Eloquent
 */
class Transaction extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'transactions';

    protected $casts = [
        'data'   => 'array',
        'state'  => TransactionStateEnum::class,
        'status' => TransactionStatusEnum::class,
        'type'   => TransactionTypeEnum::class,

    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];


    public function item(): MorphTo
    {
        return $this->morphTo();
    }


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    /** @noinspection PhpUnused */
    public function setQuantityAttribute($val): void
    {
        $this->attributes['quantity'] = sprintf('%.3f', $val);
    }
}
