<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:09:49 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Accounting;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Accounting\InvoiceStats
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $number_items current number of items
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Accounting\Invoice $invoice
 * @method static Builder|InvoiceStats newModelQuery()
 * @method static Builder|InvoiceStats newQuery()
 * @method static Builder|InvoiceStats query()
 * @method static Builder|InvoiceStats whereCreatedAt($value)
 * @method static Builder|InvoiceStats whereId($value)
 * @method static Builder|InvoiceStats whereInvoiceId($value)
 * @method static Builder|InvoiceStats whereNumberItems($value)
 * @method static Builder|InvoiceStats whereUpdatedAt($value)
 * @mixin Eloquent
 */
class InvoiceStats extends Model
{
    protected $table   = 'invoice_stats';
    protected $guarded = [];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
