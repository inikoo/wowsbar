<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sun, 17 Sep 2023 22:12:21 Malaysia Time, Pantai Lembeng, Bali, Indonesia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Market;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Market\ShopStats
 *
 * @property int $id
 * @property int $shop_id
 * @property int $number_customers
 * @property int $number_customers_state_registered
 * @property int $number_customers_state_with_appointment
 * @property int $number_customers_state_contacted
 * @property int $number_customers_state_active
 * @property int $number_customers_state_losing
 * @property int $number_customers_state_lost
 * @property int $number_orders
 * @property int $number_orders_state_creating
 * @property int $number_orders_state_submitted
 * @property int $number_orders_state_handling
 * @property int $number_orders_state_packed
 * @property int $number_orders_state_finalised
 * @property int $number_orders_state_settled
 * @property int $number_customer_websites
 * @property int $number_products
 * @property int $number_deliveries
 * @property int $number_deliveries_type_order
 * @property int $number_deliveries_type_replacement
 * @property int $number_invoices
 * @property int $number_invoices_type_invoice
 * @property int $number_invoices_type_refund
 * @property int $number_payment_service_providers
 * @property int $number_payment_accounts
 * @property int $number_payments
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Market\Shop $shop
 * @method static Builder|ShopStats newModelQuery()
 * @method static Builder|ShopStats newQuery()
 * @method static Builder|ShopStats query()
 * @method static Builder|ShopStats whereCreatedAt($value)
 * @method static Builder|ShopStats whereId($value)
 * @method static Builder|ShopStats whereNumberCustomerWebsites($value)
 * @method static Builder|ShopStats whereNumberCustomers($value)
 * @method static Builder|ShopStats whereNumberCustomersStateActive($value)
 * @method static Builder|ShopStats whereNumberCustomersStateContacted($value)
 * @method static Builder|ShopStats whereNumberCustomersStateLosing($value)
 * @method static Builder|ShopStats whereNumberCustomersStateLost($value)
 * @method static Builder|ShopStats whereNumberCustomersStateRegistered($value)
 * @method static Builder|ShopStats whereNumberCustomersStateWithAppointment($value)
 * @method static Builder|ShopStats whereNumberDeliveries($value)
 * @method static Builder|ShopStats whereNumberDeliveriesTypeOrder($value)
 * @method static Builder|ShopStats whereNumberDeliveriesTypeReplacement($value)
 * @method static Builder|ShopStats whereNumberInvoices($value)
 * @method static Builder|ShopStats whereNumberInvoicesTypeInvoice($value)
 * @method static Builder|ShopStats whereNumberInvoicesTypeRefund($value)
 * @method static Builder|ShopStats whereNumberOrders($value)
 * @method static Builder|ShopStats whereNumberOrdersStateCreating($value)
 * @method static Builder|ShopStats whereNumberOrdersStateFinalised($value)
 * @method static Builder|ShopStats whereNumberOrdersStateHandling($value)
 * @method static Builder|ShopStats whereNumberOrdersStatePacked($value)
 * @method static Builder|ShopStats whereNumberOrdersStateSettled($value)
 * @method static Builder|ShopStats whereNumberOrdersStateSubmitted($value)
 * @method static Builder|ShopStats whereNumberPaymentAccounts($value)
 * @method static Builder|ShopStats whereNumberPaymentServiceProviders($value)
 * @method static Builder|ShopStats whereNumberPayments($value)
 * @method static Builder|ShopStats whereNumberProducts($value)
 * @method static Builder|ShopStats whereShopId($value)
 * @method static Builder|ShopStats whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ShopStats extends Model
{
    protected $table = 'shop_stats';

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
