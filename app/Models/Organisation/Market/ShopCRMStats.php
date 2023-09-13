<?php
/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Wed, 13 Sep 2023 11:48:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

namespace App\Models\Organisation\Market;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Organisation\Market\ShopCRMStats
 *
 * @property int $id
 * @property int $shop_id
 * @property int $number_customers
 * @property int $number_customers_state_in_process
 * @property int $number_customers_state_registered
 * @property int $number_customers_state_active
 * @property int $number_customers_state_losing
 * @property int $number_customers_state_lost
 * @property int $number_customers_trade_state_none
 * @property int $number_customers_trade_state_one
 * @property int $number_customers_trade_state_many
 * @property int $number_prospects
 * @property int $number_prospects_state_no_contacted
 * @property int $number_prospects_state_contacted
 * @property int $number_prospects_state_not_interested
 * @property int $number_prospects_state_registered
 * @property int $number_prospects_state_invoiced
 * @property int $number_prospects_state_bounced
 * @property int $number_orders
 * @property int $number_orders_state_creating
 * @property int $number_orders_state_submitted
 * @property int $number_orders_state_handling
 * @property int $number_orders_state_packed
 * @property int $number_orders_state_finalised
 * @property int $number_orders_state_settled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Organisation\Market\Shop $shop
 * @method static Builder|ShopCRMStats newModelQuery()
 * @method static Builder|ShopCRMStats newQuery()
 * @method static Builder|ShopCRMStats query()
 * @method static Builder|ShopCRMStats whereCreatedAt($value)
 * @method static Builder|ShopCRMStats whereId($value)
 * @method static Builder|ShopCRMStats whereNumberCustomers($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateActive($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateInProcess($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateLosing($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateLost($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersStateRegistered($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersTradeStateMany($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersTradeStateNone($value)
 * @method static Builder|ShopCRMStats whereNumberCustomersTradeStateOne($value)
 * @method static Builder|ShopCRMStats whereNumberOrders($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateCreating($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateFinalised($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateHandling($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStatePacked($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateSettled($value)
 * @method static Builder|ShopCRMStats whereNumberOrdersStateSubmitted($value)
 * @method static Builder|ShopCRMStats whereNumberProspects($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateBounced($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateContacted($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateInvoiced($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateNoContacted($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateNotInterested($value)
 * @method static Builder|ShopCRMStats whereNumberProspectsStateRegistered($value)
 * @method static Builder|ShopCRMStats whereShopId($value)
 * @method static Builder|ShopCRMStats whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ShopCRMStats extends Model
{
    protected $table = 'shop_crm_stats';

    protected $guarded = [];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
