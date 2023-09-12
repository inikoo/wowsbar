<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 25 Mar 2023 01:37:07 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use App\Models\OMS\Transaction;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

/**
 * App\Models\Accounting\InvoiceTransaction
 *
 * @property int $id
 * @property int $shop_id
 * @property int $customer_id
 * @property int $order_id
 * @property int|null $invoice_id
 * @property int|null $transaction_id
 * @property string|null $item_type
 * @property int|null $item_id
 * @property string $quantity
 * @property string $net
 * @property string $discounts
 * @property string $tax
 * @property int|null $tax_band_id
 * @property array $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int|null $source_id
 * @property int|null $source_alt_id
 * @property-read Model|\Eloquent $item
 * @property-read Transaction|null $transaction
 * @method static Builder|InvoiceTransaction newModelQuery()
 * @method static Builder|InvoiceTransaction newQuery()
 * @method static Builder|InvoiceTransaction onlyTrashed()
 * @method static Builder|InvoiceTransaction query()
 * @method static Builder|InvoiceTransaction withTrashed()
 * @method static Builder|InvoiceTransaction withoutTrashed()
 * @mixin Eloquent
 */
class InvoiceTransaction extends Model
{
    use UsesTenantConnection;
    use SoftDeletes;

    protected $table = 'invoice_transactions';

    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function setQuantityAttribute($val)
    {
        $this->attributes['quantity'] = sprintf('%.3f', $val);
    }
}
