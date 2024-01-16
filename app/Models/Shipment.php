<?php

namespace App\Models;

use App\Enums\CRM\Shipping\ShippingStateEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shipment
 *
 * @property int $id
 * @property int $shipper_account_id
 * @property ShippingStateEnum $status
 * @property string|null $reference
 * @property array|null $tracking
 * @property string|null $error_message
 * @property string|null $min_state
 * @property string|null $max_state
 * @property string|null $tracked_at
 * @property int $tracked_count
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $delete_comment
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereDeleteComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereMaxState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereMinState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShipperAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereTrackedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereTrackedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereTracking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Shipment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts   = [
        'data'        => 'array',
        'credentials' => 'array',
        'tracking'    => 'array',
        'status'      => ShippingStateEnum::class
    ];
}
