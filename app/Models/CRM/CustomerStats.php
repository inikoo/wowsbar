<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:10:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\CRM;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\CRM\CustomerStats
 *
 * @property int $id
 * @property int $customer_id
 * @property int $number_images
 * @property int $filesize_images
 * @property int $number_attachments
 * @property int $filesize_attachments
 * @property Carbon|null $last_submitted_order_at
 * @property Carbon|null $last_invoiced_at
 * @property int $number_invoices
 * @property int $number_invoices_type_invoice
 * @property int $number_invoices_type_refund
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $number_customer_users
 * @property int $number_customer_users_status_active
 * @property int $number_customer_users_status_inactive
 * @property-read \App\Models\CRM\Customer $customer
 * @method static Builder|CustomerStats newModelQuery()
 * @method static Builder|CustomerStats newQuery()
 * @method static Builder|CustomerStats query()
 * @method static Builder|CustomerStats whereCreatedAt($value)
 * @method static Builder|CustomerStats whereCustomerId($value)
 * @method static Builder|CustomerStats whereFilesizeAttachments($value)
 * @method static Builder|CustomerStats whereFilesizeImages($value)
 * @method static Builder|CustomerStats whereId($value)
 * @method static Builder|CustomerStats whereLastInvoicedAt($value)
 * @method static Builder|CustomerStats whereLastSubmittedOrderAt($value)
 * @method static Builder|CustomerStats whereNumberAttachments($value)
 * @method static Builder|CustomerStats whereNumberCustomerUsers($value)
 * @method static Builder|CustomerStats whereNumberCustomerUsersStatusActive($value)
 * @method static Builder|CustomerStats whereNumberCustomerUsersStatusInactive($value)
 * @method static Builder|CustomerStats whereNumberImages($value)
 * @method static Builder|CustomerStats whereNumberInvoices($value)
 * @method static Builder|CustomerStats whereNumberInvoicesTypeInvoice($value)
 * @method static Builder|CustomerStats whereNumberInvoicesTypeRefund($value)
 * @method static Builder|CustomerStats whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CustomerStats extends Model
{
    protected $casts = [
        'last_submitted_order_at'     => 'datetime',
        'last_invoiced_at'            => 'datetime',
    ];
    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
